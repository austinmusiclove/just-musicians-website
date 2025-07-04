<?php
/**
 * Plugin Name: Hire More Musicians Artists API
 * Description: A custom plugin to expose REST APIs for managing artist posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'artists/post_id', [
        'methods' => 'GET',
        'callback' => 'get_artist_post_id',
    ]);
    register_rest_route( 'v1', 'artists/uuid', [
        'methods' => 'GET',
        'callback' => 'get_artist_uuid',
    ]);
    register_rest_route( 'v1', 'artists', [
        'methods' => 'GET',
        'callback' => 'get_artist_by_uuid',
    ]);
});


function get_artist_post_id() {
    $result = 0;
    $artist_id = $_GET['artist_id'];

    $args = [
        'post_type' => 'artist',
        'posts_per_page' => 1,
        'meta_key' => 'artist_id',
        'meta_value' => $artist_id,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $result = get_the_ID();
    }

    wp_reset_postdata();
    return $result;
}

function get_artist_uuid() {
    $result = 0;
    $artist_id = $_GET['artist_id'];

    $args = [
        'post_type' => 'artist',
        'posts_per_page' => 1,
        'meta_key' => 'artist_id',
        'meta_value' => $artist_id,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $result = get_field('artist_uuid');
    }

    wp_reset_postdata();
    return $result;
}

function get_artist_by_uuid() {
    $result = 0;
    $artist_uuid = $_GET['artist_uuid'];

    $args = [
        'post_type' => 'artist',
        'posts_per_page' => 1,
        'meta_key' => 'artist_uuid',
        'meta_value' => $artist_uuid,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $result = [
            'ID' => get_the_ID(),
            'artist_uuid' => get_field('artist_uuid'),
            'artist_id' => get_field('artist_id'),
            'name' => get_field('name'),
            'city' => get_field('city'),
            'state' => get_field('state'),
            'type' => get_field('type'),
            'spotify_artist_id' => get_field('spotify_artist_id'),
            'email' => get_field('email'),
            'bio' => get_field('bio'),
            'phone' => get_field('phone'),
            'website' => get_field('website'),
            'claimed_genre' => get_field('claimed_genre'),
            'macro_genres' => array_filter([get_field('macro_genre_1'), get_field('macro_genre_2'), get_field('macro_genre_3')]),
            'female_led' => get_field('female_led'),
            'ensemble_size' => get_field('ensemble_size'),
            'instagram_handle' => get_field('instagram_handle'),
            'tiktok_handle' => get_field('tiktok_handle'),
            'x_handle' => get_field('x_handle'),
            'facebook_url' => get_field('facebook_url'),
            'youtube_url' => get_field('youtube_url'),
            'bandcamp_url' => get_field('bandcamp_url'),
            'soundcloud_url' => get_field('soundcloud_url'),
            'venues_played_verified' => get_post_meta(get_the_ID(), 'venues_played_verified', true), // returns array of venue post ids
        ];
    }
    wp_reset_postdata();
    return $result;
}

function get_artist($post_id) {
    $result = 0;

    $args = [
        'post_type' => 'artist',
        'p' => $post_id,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();

        // Get genres
        $genres = [];
        $terms = get_the_terms(get_the_ID(), 'genre');
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) { $genres[] = $term->term_id; }
        }

        $result = [
            'ID' => get_the_ID(),
            'artist_uuid' => get_field('artist_uuid'),
            'artist_id' => get_field('artist_id'),
            'name' => get_field('name'),
            'city' => get_field('city'),
            'state' => get_field('state'),
            'type' => get_field('type'),
            'spotify_artist_id' => get_field('spotify_artist_id'),
            'email' => get_field('email'),
            'bio' => get_field('bio'),
            'phone' => get_field('phone'),
            'website' => get_field('website'),
            'claimed_genre' => get_field('claimed_genre'),
            'macro_genres' => array_filter([get_field('macro_genre_1'), get_field('macro_genre_2'), get_field('macro_genre_3')]),
            'genres' => $genres,
            'female_led' => get_field('female_led'),
            'ensemble_size' => get_field('ensemble_size'),
            'instagram_handle' => get_field('instagram_handle'),
            'tiktok_handle' => get_field('tiktok_handle'),
            'x_handle' => get_field('x_handle'),
            'facebook_url' => get_field('facebook_url'),
            'youtube_url' => get_field('youtube_url'),
            'bandcamp_url' => get_field('bandcamp_url'),
            'soundcloud_url' => get_field('soundcloud_url'),
            'venues_played_verified' => get_post_meta(get_the_ID(), 'venues_played_verified', true), // returns array of venue post ids
        ];
    }
    wp_reset_postdata();
    return $result;
}
