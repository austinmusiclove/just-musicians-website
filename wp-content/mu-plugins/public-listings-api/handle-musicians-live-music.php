<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function hm_public_api_handle_musicians_live_music() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');

    $zip = isset($_GET['zip']) ? sanitize_text_field(wp_unslash($_GET['zip'])) : '';

    if (empty($zip)) {
        wp_send_json([
            'error' => 'Missing required parameter: zip',
            'message' => 'Provide a US or Canada postal code, e.g. ?zip=78701',
        ], 400);
    }

    $loc = function_exists('hm_location_get_by_pc') ? hm_location_get_by_pc($zip) : null;
    if (!$loc || empty($loc->lat) || empty($loc->lng)) {
        wp_send_json([
            'error' => 'Invalid zip code',
            'message' => 'Could not resolve the provided postal code to a location.',
            'zip'    => $zip,
        ], 400);
    }

    $distance = isset($_GET['distance']) && is_numeric($_GET['distance']) ? (float) $_GET['distance'] : 40;
    $page     = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

    $result = get_listings([
        'lat'           => $loc->lat,
        'lng'           => $loc->lng,
        'distance'      => $distance,
        'genres'        => hm_public_api_parse_list($_GET['genres'] ?? ''),
        'ensemble_size' => hm_public_api_parse_list($_GET['ensemble_size'] ?? ''),
        'verified'      => true,
        'page'          => $page,
    ]);

    $musicians = [];
    foreach ($result['listings'] as $listing) {
        $genres = [];
        if (!empty($listing['genre'])) {
            $genres = array_map(fn($genre) => $genre->name, $listing['genre']);
        }
        $musicians[] = [
            'name'          => $listing['name'],
            'genres'        => $genres,
            'city'          => $listing['city'],
            'state'         => $listing['state'],
            'description'   => $listing['description'],
            'bio'           => $listing['bio'],
            'thumbnail_url' => $listing['thumbnail_url'],
            'permalink'     => $listing['permalink'],
        ];
    }

    wp_send_json([
        'location' => [
            'zip'   => $zip,
            'city'  => $loc->city,
            'state' => $loc->state,
            'lat'   => (float) $loc->lat,
            'lng'   => (float) $loc->lng,
        ],
        'query' => [
            'distance'      => $distance,
            'genres'        => $result['valid_genres'],
            'ensemble_size' => $result['valid_ensemble_sizes'],
            'verified'      => true,
            'page'          => $page,
        ],
        'max_num_results' => $result['max_num_results'],
        'next_page'       => $result['next_page'],
        'musicians'       => $musicians,
    ]);
}

function hm_public_api_parse_list($input) {
    if (empty($input)) { return []; }
    if (is_array($input)) {
        $items = $input;
    } else {
        $items = explode(',', (string) $input);
    }
    return array_values(array_filter(array_map('trim', $items), fn($item) => $item !== ''));
}
