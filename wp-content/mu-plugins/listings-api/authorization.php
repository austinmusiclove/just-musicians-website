<?php
// Handles auth functions for listing apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


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
