<?php
/**
 * Plugin Name: Just Musicians User Management API
 * Description: A custom plugin to expose REST APIs for doing user management operations
 * Version: 1.0
 * Author: John Filippone
**/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Rest APIs
add_action('rest_api_init', function () {
    register_rest_route('user/v1', 'email-verified', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'is_email_verified'
    ));
    register_rest_route('user/v1', 'profiles', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'get_urser_profiles'
    ));
});


function send_account_activation_link($email, $account_identifier) {
    $link = site_url() . "/email-verification/?aci=" . $account_identifier;
    $message = 'Thank you for creating an account with Just Musicians. Please click this link to verify your email and activate you account: ' . $link;
    wp_mail($email, 'Verify your email to activate your Just Musicians account', $message);
}

// Called by email verification page that is linked in the email sent to new users
function activate_account($account_identifier) {
    // get user
    $account_user = get_users(array(
        'meta_key' => 'account_identifier',
        'meta_value' => $account_identifier
    ));
    // update the user email_verified field
    $update_success = update_user_meta($account_user[0]->ID, "email_verified", true);
    if ($update_success == true) {
        return "Your email has been verified. You may now close this browser tab.";
    } else {
        return "Error updating account record. Please contact the admin for assistance on your subscription at john@justmusicians.com";
    }
}

// is current user email verified
function is_email_verified() {
    $email_verified = get_user_meta(wp_get_current_user()->ID, "email_verified");
    return $email_verified[0];
    if (!isset($email_verified) or is_null($email_verified) or !is_array($email_verified)) {
        return false;
    } else {
        return $email_verified[0];
    }
}

// get current user profiles
function get_urser_profiles() {
    $results = array();
    $user_profiles = get_user_meta(wp_get_current_user()->ID, "profiles");
    if (!isset($user_profiles) or is_null($user_profiles) or !is_array($user_profiles)) {
        return $results;
    } else {
        // for each profile get name
        for ($index = 0; $index < count($user_profiles[0]); $index++) {
            $profile_post_id = $user_profiles[0][$index];

            // if status not publish, skip
            $post_status = get_post_status($profile_post_id);
            if ($post_status != "publish") { continue; }

            $name = get_post_meta($profile_post_id, "name")[0];
            array_push($results, array(
                "post_id" => $profile_post_id,
                "name" => $name
            ));
        }
        return $results;
    }
}

function add_listing_by_invitation_code($listing_invitation_code) {
    // Validate listing invitation code
    $code_post = validate_temporary_code($listing_invitation_code);
    if (is_wp_error($code_post)) {
        if ($code_post->get_error_code() == 'invalid_code') { return new WP_Error('invalid_link', 'Invalid listing invitation link'); }
        if ($code_post->get_error_code() == 'expired_code') { return new WP_Error('expired_link', 'Expired listing invitation link'); }
        exit;
        return $code_post;
    }

    // Check if the user is logged in
    if (!is_user_logged_in()) {
        return new WP_Error('user_not_logged_in', 'You must be logged in to use this code.');
    }

    // Get the listings (array of post IDs) from the tmp_code post
    $listing_ids = get_post_meta($code_post->ID, 'listings', true);
    $valid_listings = (empty($listing_ids) or !is_array($listing_ids)) ? [] : array_filter($listing_ids, function ($listing) {
        $post = get_post($listing);
        return $post and $post->post_status === 'publish';
    });

    // Check if listings are found in the tmp_code post
    if (empty($valid_listings) or !is_array($valid_listings)) {
        return new WP_Error('no_listings', 'No listings associated with this code.');
    }

    // Add the listings to the current user's meta field
    $current_user = wp_get_current_user();
    $current_listings = get_user_meta($current_user->ID, 'listings', true);
    if (empty($current_listings)) { $current_listings = array(); }
    $current_listings = array_merge($current_listings, $valid_listings);
    $current_listings = array_unique($current_listings);

    // Save the updated listings to the user meta
    update_user_meta($current_user->ID, 'listings', $current_listings);

    return true; // Success
}

function validate_temporary_code($temporary_code) {
    // Get temporary code
    $args = array(
        'post_type' => 'tmp_code',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => 'code',
                'value' => $temporary_code,
                'compare' => '=',
            ]
        ],
    );
    $tmp_code_query = new WP_Query($args);
    if (!$tmp_code_query->have_posts()) {
        wp_reset_postdata();
        return new WP_Error('invalid_code', 'Invalid Code');
    }
    $tmp_code_post = $tmp_code_query->posts[0];

    // Check if the code has expired
    $expiration_timestamp = get_post_meta($tmp_code_post->ID, 'expiration_timestamp', true);
    if ($expiration_timestamp and $expiration_timestamp < time()) {
        wp_reset_postdata();
        return new WP_Error('expired_code', 'This code has expired.');
    }

    wp_reset_postdata();
    return $tmp_code_post;
}
