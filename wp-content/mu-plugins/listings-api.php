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
require_once 'listings-api/get-listing.php';
require_once 'listings-api/get-listings.php';
require_once 'listings-api/create-listing.php';
require_once 'listings-api/update-listing.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'listings', [
        'methods' => 'GET',
        'callback' => 'get_listings_request_handler',
    ]);
    register_rest_route( 'v1', 'listings', [
        'methods' => 'POST',
        'callback' => 'post_listing_request_handler',
        'permission_callback' => 'check_post_listing_auth',
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

function post_listing_request_handler($request) {
    $args = get_sanitized_listing_args();
    if (!empty($args['post_id'])) {
        return _update_listing($args);
    } else {
        return _create_listing($args);
    }
}



// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
function get_sanitized_listing_args() {
    $sanitized_args = [
        'meta_input'      => [],
        'tax_input'       => [],
        '_thumbnail_file' => isset($_FILES['cropped-thumbnail']) ? $_FILES['cropped-thumbnail'] : null,
    ];
    // Post Id
    if (isset($_POST['post_id']))          { $sanitized_args['ID']                           = sanitize_text_field($_POST['post_id']); }

    // Name
    if (isset($_POST['listing_name']))     { $sanitized_args['post_title']                   = sanitize_text_field($_POST['listing_name']); }
    if (isset($_POST['listing_name']))     { $sanitized_args['meta_input']['name']           = sanitize_text_field($_POST['listing_name']); }

    // Meta Fields
    if (isset($_POST['description']))      { $sanitized_args['meta_input']['description']    = sanitize_text_field($_POST['description']); }
    if (isset($_POST['city']))             { $sanitized_args['meta_input']['city']           = sanitize_text_field($_POST['city']); }
    if (isset($_POST['state']))            { $sanitized_args['meta_input']['state']          = sanitize_text_field($_POST['state']); }
    if (isset($_POST['zip_code']))         { $sanitized_args['meta_input']['zip_code']       = sanitize_text_field($_POST['zip_code']); }
    if (isset($_POST['bio']))              { $sanitized_args['meta_input']['bio']            = sanitize_textarea_field($_POST['bio']); }

    // Taxonomies
    if (isset($_POST['categories']) )      { $sanitized_args['tax_input']['mcategory']       = array_filter(array_map('sanitize_text_field', $_POST['categories'])); }
    if (isset($_POST['genres']))           { $sanitized_args['tax_input']['genre']           = array_filter(array_map('sanitize_text_field', $_POST['genres'])); }
    if (isset($_POST['subgenres']))        { $sanitized_args['tax_input']['subgenre']        = array_filter(array_map('sanitize_text_field', $_POST['subgenres'])); }
    if (isset($_POST['instrumentations'])) { $sanitized_args['tax_input']['instrumentation'] = array_filter(array_map('sanitize_text_field', $_POST['instrumentations'])); }
    if (isset($_POST['settings']))         { $sanitized_args['tax_input']['setting']         = array_filter(array_map('sanitize_text_field', $_POST['settings'])); }

    return $sanitized_args;
}



function check_post_listing_auth() {
    // User must be logged in
    if (!is_user_logged_in()) {
        return new WP_Error(401, 'Must be logged in to perform this action');
    }

    // Admin can create or edit any post
    if (current_user_can('administrator')) {
        return true;
    }

    // Any user can create a listing
    if (empty($_POST['post_id'])) {
        return true;
    }

    // Handle post update
    if (!empty($_POST['post_id'])) {
        $user_listings = get_user_meta(get_current_user_id(), 'listings', true);

        // User can only edit their own listing
        if (in_array($_POST['post_id'], $user_listings)) {
            return true;
        } else {
            return new WP_Error(401, 'You are not authorized to edit this listing');
        }

    }
}
