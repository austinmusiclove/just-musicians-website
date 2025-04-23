<?php
/**
 * Plugin Name: Just Musicians Collections API
 * Description: A custom plugin to expose REST APIs for managing collection posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1/collections', '/(?P<post_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_collection_listings',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1/collections', '', [
        'methods' => 'GET',
        'callback' => 'get_collections',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1/collections', '', [
        'methods' => 'POST',
        'callback' => 'create_collection',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1/collections', '/(?P<post_id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_collection',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1/collections', '/(?P<post_id>\d+)', [
        'methods' => 'POST',
        'callback' => 'remove_listing_from_collection',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1/collections', '/(?P<post_id>\d+)', [
        'methods' => 'POST',
        'callback' => 'add_listing_to_collection',
        'permission_callback' => 'is_user_logged_in',
    ]);
});


function get_collection_listings($request) {
    // if postid is favorites get favorites from user
    // else get collection with listings
    // page by 10
}
function get_collections() {
    $collections = [];
    $current_user_id = get_current_user_id();

    // Get Favorites
    $favorites = get_user_meta($current_user_id, 'favorites', true);
    $collections[] = [
        'post_id'        => 'favorites',
        'name'           => 'Favorites',
        'listings'       => $favorites,
        'thumbnail_urls' => [get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp'],
    ];
    return $collections;

    // Get Collections

    // Show User Collections
    $collections = get_user_meta($current_user_id, 'collections', true);
    if ( $collections and count($collections) > 0 ) {

        // Query the posts
        $args = [
            'post_type'      => 'collection',
            'post__in'       => $collections,
            'post_status'    => 'publish',
            'orderby'        => 'post__in',
            'posts_per_page' => -1
        ];
        $query = new WP_Query($args);
        if ($query->have_posts()) { ?>

            <!-- Display user's collections -->

            <?php while ($query->have_posts()) {
                $query->the_post();
                $thumbnail_url = null; //get_the_post_thumbnail_url(get_the_ID(), 'standard-listing');
                echo get_template_part('template-parts/account/collection-listing', '', [
                    'post_id' => get_the_ID(),
                    'name' => get_post_meta(get_the_ID(), 'name', true),
                    'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
                    'num_listings' => 0,
                    'allow_delete' => true,
                ]);
            }
        }
    }


    $result = array();
    $user_id = $_GET['user_id'];
    $args = array(
        'post_type' => 'collection',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => 'user',
                'value' => $user_id,
            )
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'name' => get_field('name'),
                'user' => get_field('user'),
                'listings' => get_field('listings'),
            ));
        }
    }
    $favorites = get_user_meta($user_id, 'favorites', false);
    array_push($result, array(
        'name' => 'Favorites',
        'listings' => $favorites
    ));
    return $result;
}
function create_collection($request) {
}
function delete_collection($request) {
}
function remove_listing_from_collection($request) {
}
function add_listing_to_collection($request) {
}
