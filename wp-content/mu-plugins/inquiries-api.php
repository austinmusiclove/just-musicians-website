<?php
/**
 * Plugin Name: Just Musicians Inquiries API
 * Description: A custom plugin to expose REST APIs for managing inquiry posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'inquiries-api/authorization.php';
require_once 'inquiries-api/parse-args.php';
require_once 'inquiries-api/get-inquiries.php';
require_once 'inquiries-api/get-requests.php';
require_once 'inquiries-api/create-inquiry.php';
require_once 'inquiries-api/add-listing-to-inquiry.php';


// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/inquiries', [
        'methods' => 'POST',
        'callback' => 'create_inquiry_request_handler',
        'permission_callback' => 'user_logged_in',
    ]);
    register_rest_route('v1', '/inquiries/(?P<inquiry_id>[a-zA-Z0-9_-]+)/listings', [
        'methods' => 'POST',
        'callback' => 'add_listing_to_inquiry_request_handler',
        'permission_callback' => 'user_owns_inquiry',
    ]);
});



function create_inquiry_request_handler($request) {
    $args = get_sanitized_inquiry_args();
    return create_user_inquiry($args);
}
function add_listing_to_inquiry_request_handler($request) {
    $inquiry_id = $request->get_param('inquiry_id');
    $listing_id    = $request->get_param('listing_id');
    return add_listing_to_inquiry($inquiry_id, $listing_id);
}
