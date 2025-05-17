<?php
/**
 * Plugin Name: Hire More Musicians Collections API
 * Description: A custom plugin to expose REST APIs for managing collection posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'collections-api/authorization.php';
require_once 'collections-api/get-collections.php';
require_once 'collections-api/create-collection.php';
require_once 'collections-api/delete-collection.php';
require_once 'collections-api/add-listing-to-collection.php';
require_once 'collections-api/remove-listing-from-collection.php';


// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/collections', [
        'methods' => 'GET',
        'callback' => 'get_collections_request_handler',
        'permission_callback' => 'user_logged_in',
    ]);
    register_rest_route( 'v1', '/collections', [
        'methods' => 'POST',
        'callback' => 'create_collection_request_handler',
        'permission_callback' => 'user_logged_in',
    ]);
    register_rest_route( 'v1', '/collections/(?P<collection_id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_collection_request_handler',
        'permission_callback' => 'user_owns_collection',
    ]);
    register_rest_route('v1', '/collections/(?P<collection_id>[a-zA-Z0-9_-]+)/listings', [
        'methods' => 'POST',
        'callback' => 'add_listing_to_collection_request_handler',
        'permission_callback' => 'user_owns_collection',
    ]);
    register_rest_route('v1', '/collections/(?P<collection_id>[a-zA-Z0-9_-]+)/listings/(?P<listing_id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'remove_listing_from_collection_request_handler',
        'permission_callback' => 'user_owns_collection',
    ]);
});


function get_collections_request_handler($request) {
    return get_user_collections([ 'page' => $request['page'], ]);
}

function create_collection_request_handler($request) {
    $collection_name = $request->get_param('collection_name');
    return create_user_collection($collection_name);
}
function delete_collection_request_handler($request) {
    $collection_id = $request->get_param('collection_id');
    return delete_collection($collection_id);
}
function add_listing_to_collection_request_handler($request) {
    $collection_id = $request->get_param('collection_id');
    $listing_id    = $request->get_param('listing_id');
    return add_listing_to_collection($collection_id, $listing_id);
}
function remove_listing_from_collection_request_handler($request) {
    $collection_id = $request->get_param('collection_id');
    $listing_id    = $request->get_param('listing_id');
    return remove_listing_from_collection($collection_id, $listing_id);
}
