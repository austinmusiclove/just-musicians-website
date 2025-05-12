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
    register_rest_route( 'v1', 'collections', [
        'methods' => 'GET',
        'callback' => 'get_collections',
    ]);
    register_rest_route( 'v1', 'collections', [
        'methods' => 'POST',
        'callback' => 'create_collection',
    ]);
    register_rest_route( 'v1', 'collections', [
        'methods' => 'DELETE',
        'callback' => 'delete_collection',
    ]);
    register_rest_route( 'v1', 'collections', [
        'methods' => 'PUT',
        'callback' => 'remove_listing_from_collection',
    ]);
    register_rest_route( 'v1', 'collections', [
        'methods' => 'PUT',
        'callback' => 'add_listing_to_collection',
    ]);
});


function get_collections() {
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
function create_collection() {
}
function delete_collection() {
}
function remove_listing_from_collection() {
}
function add_listing_to_collection() {
}
