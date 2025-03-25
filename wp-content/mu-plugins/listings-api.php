<?php

function get_listings($args) {
    $results = [];
    $search_term = sanitize_text_field($args['search']);
    $name_search_term = sanitize_text_field($args['name_search']);
    $types = rest_sanitize_array($args['types']);
    $valid_types = validate_listing_types($types);
    $valid_genres = validate_tax_input($args['genres'], 'genre');
    $valid_subgenres = validate_tax_input($args['subgenres'], 'subgenre');
    $valid_instrumentations = validate_tax_input($args['instrumentations'], 'instrumentation');
    $valid_settings = validate_tax_input($args['settings'], 'setting');
    $valid_tags = validate_tax_input($args['tags'], 'tag');
    $verified = rest_sanitize_boolean($args['verified']);
    $sanitized_page = sanitize_text_field($args['page']);
    $page = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page = $page + 1;

    $query_args = [
        'post_type' => 'listing',
        'posts_per_page' => 10,
        'paged' => $page,
    ];
    if (!empty($search_term)) {
        $query_args['s'] = $args['search'];
    }
    $meta_queries = [];
    if (!empty($name_search_term)) {
        array_push($meta_queries, [
            'key' => 'name',
            'value' => $name_search_term,
            'compare' => 'LIKE',
        ]);
    }
    if (!empty($types)) {
        array_push($meta_queries, [
            'key' => 'type',
            'value' => $valid_types,
            'compare' => 'IN',
        ]);
    }
    if (!empty($verified)) {
        array_push($meta_queries, [
            'key' => 'verified',
            'value' => $verified,
        ]);
    }
    $query_args['meta_query'] = count($meta_queries) == 0 ? null : count($meta_queries) == 1 ? [...$meta_queries] : [
        'relation' => 'AND',
        ...$meta_queries,
    ];
    $tax_queries = [];
    if (!empty($valid_genres)) {
        array_push($tax_queries, [
            'taxonomy' => 'genre',
            'field' => 'name',
            'terms' => $valid_genres,
            'compare' => 'IN',
        ]);
    }
    if (!empty($valid_subgenres)) {
        array_push($tax_queries, [
            'taxonomy' => 'subgenre',
            'field' => 'name',
            'terms' => $valid_subgenres,
            'compare' => 'IN',
        ]);
    }
    if (!empty($valid_instrumentations)) {
        array_push($tax_queries, [
            'taxonomy' => 'instrumentation',
            'field' => 'name',
            'terms' => $valid_instrumentations,
            'compare' => 'IN',
        ]);
    }
    if (!empty($valid_settings)) {
        array_push($tax_queries, [
            'taxonomy' => 'setting',
            'field' => 'name',
            'terms' => $valid_settings,
            'compare' => 'IN',
        ]);
    }
    if (!empty($valid_tags)) {
        array_push($tax_queries, [
            'taxonomy' => 'tag',
            'field' => 'name',
            'terms' => $valid_tags,
            'compare' => 'IN',
        ]);
    }
    $query_args['tax_query'] = count($tax_queries) == 0 ? null : count($tax_queries) == 1 ? [...$tax_queries] : [
        'relation' => 'AND',
        ...$tax_queries,
    ];
    $query = new WP_Query($query_args);
    while ($query->have_posts()) {
        $query->the_post();

        // Get valid video links for this listing
        $youtube_video_urls = get_field('youtube_video_urls');
        $youtube_video_ids = [];
        if ($youtube_video_urls) {
            foreach($youtube_video_urls as $url) {
              if (preg_match('/(?:https?:\/\/(?:www\.)?youtube\.com(?:\/[^\/]+)?\?v=([a-zA-Z0-9_-]+))/', $url, $matches)) { array_push($youtube_video_ids, $matches[1]); }
            }
        }

        array_push($results, [
            'title' => get_the_title(),
            'name' => get_field('name'),
            'city' => get_field('city'),
            'state' => get_field('state'),
            'description' => get_field('description'),
            'genre' => get_the_terms(get_the_ID(), 'genre'),
            'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'small'),
            'website' => get_field('website'),
            'facebook_url' => get_field('facebook_url'),
            'instagram_url' => get_field('instagram_url'),
            'x_url' => get_field('x_url'),
            'youtube_url' => get_field('youtube_url'),
            'tiktok_url' => get_field('tiktok_url'),
            'bandcamp_url' => get_field('bandcamp_url'),
            'spotify_artist_url' => get_field('spotify_artist_url'),
            'apple_music_artist_url' => get_field('apple_music_artist_url'),
            'soundcloud_url' => get_field('soundcloud_url'),
            'verified' => get_field('verified'),
            'youtube_video_urls' => get_field('youtube_video_urls'),
            'youtube_video_ids' => $youtube_video_ids,
        ]);
    }

    return [
        'listings' => $results,
        'valid_types' => $valid_types,
        'valid_genres' => $valid_genres,
        'valid_subgenres' => $valid_subgenres,
        'valid_instrumentations' => $valid_instrumentations,
        'valid_settings' => $valid_settings,
        'valid_tags' => $valid_tags,
        'next_page' => $next_page,
    ];
}
function validate_listing_types($types) {
    $default_types = ['Artist', 'Musician', 'Producer', 'DJ', 'Sound Engineer', 'Band'];
    return array_values(array_intersect($types, $default_types));
}
function validate_tax_input($tax_input, $taxonomy) {
    $input = rest_sanitize_array($tax_input);
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'fields' => 'names',
        'hide_empty' => false,
    ]);
    $valid_input = array_map('stripslashes', array_filter($input, fn($item) => in_array(stripslashes($item), $terms, true)));
    return $valid_input;
}

add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'listings', [
        'methods' => 'GET',
        'callback' => 'get_listings_request_handler',
    ]);
});
function get_listings_request_handler($request) {
    return get_listings([
        'search' => $request['s'],
        'types' => $request['types'],
        'genres' => $request['genres'],
        'subgenres' => $request['subgenres'],
        'instrumentation' => $request['instrumentation'],
        'settings' => $request['settings'],
        'tags' => $request['tags'],
        'verified' => $request['verified'],
        'page' => $request['page'],
    ]);
}
