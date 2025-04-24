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
require_once 'listings-api/search-algo.php';
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


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
// for taxonomy args you can pass in [""] if you want to remove all terms; omitting the arg will not alter the terms
function get_sanitized_listing_args() {
    $sanitized_args = [
        'post_type'       => 'listing',
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                { $sanitized_args['ID']                                   = sanitize_text_field($_POST['post_id']); }

    // Name
    if (isset($_POST['listing_name']))           { $sanitized_args['post_title']                           = sanitize_text_field($_POST['listing_name']); }
    if (isset($_POST['listing_name']))           { $sanitized_args['meta_input']['name']                   = sanitize_text_field($_POST['listing_name']); }

    // Meta Fields
    if (isset($_POST['description']))            { $sanitized_args['meta_input']['description']            = sanitize_text_field($_POST['description']); }
    if (isset($_POST['city']))                   { $sanitized_args['meta_input']['city']                   = sanitize_text_field($_POST['city']); }
    if (isset($_POST['state']))                  { $sanitized_args['meta_input']['state']                  = sanitize_text_field($_POST['state']); }
    if (isset($_POST['zip_code']))               { $sanitized_args['meta_input']['zip_code']               = sanitize_text_field($_POST['zip_code']); }
    if (isset($_POST['bio']))                    { $sanitized_args['meta_input']['bio']                    = sanitize_textarea_field($_POST['bio']); }
    if (isset($_POST['listing_email']))          { $sanitized_args['meta_input']['email']                  = sanitize_email($_POST['listing_email']); }
    if (isset($_POST['phone']))                  { $sanitized_args['meta_input']['phone']                  = sanitize_text_field($_POST['phone']); }
    if (isset($_POST['instagram_handle']))       { $sanitized_args['meta_input']['instagram_handle']       = sanitize_text_field($_POST['instagram_handle']); }
    if (isset($_POST['instagram_url']))          { $sanitized_args['meta_input']['instagram_url']          = sanitize_url($_POST['instagram_url']); }
    if (isset($_POST['tiktok_handle']))          { $sanitized_args['meta_input']['tiktok_handle']          = sanitize_text_field($_POST['tiktok_handle']); }
    if (isset($_POST['tiktok_url']))             { $sanitized_args['meta_input']['tiktok_url']             = sanitize_url($_POST['tiktok_url']); }
    if (isset($_POST['x_handle']))               { $sanitized_args['meta_input']['x_handle']               = sanitize_text_field($_POST['x_handle']); }
    if (isset($_POST['x_url']))                  { $sanitized_args['meta_input']['x_url']                  = sanitize_url($_POST['x_url']); }
    if (isset($_POST['website']))                { $sanitized_args['meta_input']['website']                = sanitize_url($_POST['website']); }
    if (isset($_POST['facebook_url']))           { $sanitized_args['meta_input']['facebook_url']           = sanitize_url($_POST['facebook_url']); }
    if (isset($_POST['youtube_url']))            { $sanitized_args['meta_input']['youtube_url']            = sanitize_url($_POST['youtube_url']); }
    if (isset($_POST['bandcamp_url']))           { $sanitized_args['meta_input']['bandcamp_url']           = sanitize_url($_POST['bandcamp_url']); }
    if (isset($_POST['spotify_artist_url']))     { $sanitized_args['meta_input']['spotify_artist_url']     = sanitize_url($_POST['spotify_artist_url']); }
    if (isset($_POST['spotify_artist_id']))      { $sanitized_args['meta_input']['spotify_artist_id']      = sanitize_text_field($_POST['spotify_artist_id']); }
    if (isset($_POST['apple_music_artist_url'])) { $sanitized_args['meta_input']['apple_music_artist_url'] = sanitize_url($_POST['apple_music_artist_url']); }
    if (isset($_POST['soundcloud_url']))         { $sanitized_args['meta_input']['soundcloud_url']         = sanitize_url($_POST['soundcloud_url']); }
    if (isset($_POST['ensemble_size']))          { $sanitized_args['meta_input']['ensemble_size']          = custom_sanitize_array($_POST['ensemble_size']); }
    if (isset($_POST['youtube_video_urls']))     { $sanitized_args['meta_input']['youtube_video_urls']     = custom_sanitize_array($_POST['youtube_video_urls']); }

    // Taxonomies
    if (isset($_POST['categories']) )            { $sanitized_args['tax_input']['mcategory']               = custom_sanitize_array($_POST['categories']); }
    if (isset($_POST['genres']))                 { $sanitized_args['tax_input']['genre']                   = custom_sanitize_array($_POST['genres']); }
    if (isset($_POST['subgenres']))              { $sanitized_args['tax_input']['subgenre']                = custom_sanitize_array($_POST['subgenres']); }
    if (isset($_POST['instrumentations']))       { $sanitized_args['tax_input']['instrumentation']         = custom_sanitize_array($_POST['instrumentations']); }
    if (isset($_POST['settings']))               { $sanitized_args['tax_input']['setting']                 = custom_sanitize_array($_POST['settings']); }
    if (isset($_POST['keywords']))               { $sanitized_args['tax_input']['keyword']                 = custom_sanitize_array($_POST['keywords']); }

    // Files
    if (isset($_FILES['cropped-thumbnail']))     { $sanitized_args['_thumbnail_file']                      = custom_sanitize_file($_FILES['cropped-thumbnail']); }


    return $sanitized_args;
}


// Cleans name property of file
function custom_sanitize_file($file) {
    $file['name'] = sanitize_file_name($file['name']);
    return $file;
}

// sanitize array, remove blank values with array_filter, reindex array with array_values
// useful with array inputs where i always pass a blank so that the user has a way to erase all options; otherwise no argument is passed to the back end and no edit happens
// reindexing is useful so that json_encode turns it into an array instead of an object
function custom_sanitize_array($arr) {
    return array_values(array_filter(array_map('sanitize_text_field', rest_sanitize_array($arr))));
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
        if (is_array($user_listings) and in_array($_POST['post_id'], $user_listings)) {
            return true;
        } else {
            return new WP_Error(401, 'You are not authorized to edit this listing');
        }

    }
}


function check_delete_listing_auth($request) {
    // User must be logged in
    if (!is_user_logged_in()) {
        return new WP_Error(401, 'Must be logged in to perform this action');
    }

    // Admin can delete any post
    if (current_user_can('administrator')) {
        return true;
    }

    // Any user can create a listing
    $post_id = isset($request['post_id']) ? intval($request['post_id']) : 0;
    if (!$post_id || get_post_type($post_id) !== 'listing') {
        return new WP_Error(400, 'Invalid listing ID');
    }

    // Check current user's listings
    $user_listings = get_user_meta(get_current_user_id(), 'listings', true);

    // User can only delete their own listing
    if (is_array($user_listings) and in_array($post_id, $user_listings)) {
        return true;
    } else {
        return new WP_Error(401, 'You are not authorized to delete this listing');
    }

}


function update_search_rank($post_id) {
    $rank = 0;

    // Loop through fields to check if they have a value
    $fields_to_check = [
        'name', 'description', 'city', 'state', 'zip_code', 'bio', 'ensemble_size',
        'website', 'instagram_url', 'youtube_url', 'spotify_artist_url',
        'apple_music_artist_url', 'youtube_video_urls', 'venues_played_verified'
    ];
    foreach ( $fields_to_check as $field ) {
        if ( ! empty( get_post_meta( $post_id, $field, true ) ) ) {
            $rank++;
        }
    }

    update_post_meta( $post_id, 'rank', $rank );
}
