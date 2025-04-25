<?php
/**
 * Plugin Name: Just Musicians Collections API
 * Description: A custom plugin to expose REST APIs for managing collection posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'collections-api/get-collections.php';


// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1/collections', '', [
        'methods' => 'GET',
        'callback' => 'get_collections_request_handler',
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


function get_collections_request_handler($request) {
    return get_collections([ 'page' => $request['page'], ]);
}

function create_collection($request) {
}
function delete_collection($request) {
}
function remove_listing_from_collection($request) {
}
function add_listing_to_collection($request) {
}

