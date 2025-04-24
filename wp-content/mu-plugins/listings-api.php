<?php
/**
 * Plugin Name: Just Musicians Listings API
 * Description: A custom plugin to expose REST APIs for managing listing posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
//require_once 'listings-api/search-algo.php'; // Failed experiement
require_once 'listings-api/calculated-fields.php';
require_once 'listings-api/parse-args.php';
require_once 'listings-api/authorization.php';
require_once 'listings-api/get-listing.php';
require_once 'listings-api/get-listings.php';
require_once 'listings-api/create-listing.php';
require_once 'listings-api/update-listing.php';
require_once 'listings-api/delete-listing.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1/listings', '', [
        'methods' => 'GET',
        'callback' => 'get_listings_request_handler',
    ]);
    register_rest_route( 'v1/listings', '/(?P<auuid>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'get_listing_by_auuid_request_handler',
    ]);
    register_rest_route( 'v1/listings', '', [
        'methods' => 'POST',
        'callback' => 'post_listing_request_handler',
        'permission_callback' => 'check_post_listing_auth',
    ]);
    register_rest_route( 'v1/listings', '/(?P<post_id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_listing_request_handler',
        'permission_callback' => 'check_delete_listing_auth',
    ]);
});

function get_listings_request_handler($request) {
    return get_listings([
        'search' => $request['s'],
        'types' => $request['types'],
        'categories' => $request['categories'],
        'genres' => $request['genres'],
        'subgenres' => $request['subgenres'],
        'instrumentation' => $request['instrumentation'],
        'settings' => $request['settings'],
        'tags' => $request['tags'],
        'verified' => $request['verified'],
        'page' => $request['page'],
    ]);
}

function get_listing_by_auuid_request_handler($request) {
    $auuid = $request['auuid'];
    return get_listings_by_auuid($auuid);
}

function post_listing_request_handler($request) {
    $args = get_sanitized_listing_args();
    if (!empty($args['post_id'])) {
        return _update_listing($args);
    } else {
        return _create_listing($args);
    }
}

function delete_listing_request_handler($request) {
    return _delete_listing(['post_id' => $request['post_id']]);
}
