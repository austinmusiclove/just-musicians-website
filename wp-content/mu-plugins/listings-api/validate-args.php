<?php
// Handles arg validation for listing apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


function validate_listing_args() {

    // Cover image
    if (!isset($_POST['cover_image_meta'])) {
        return new WP_Error('missing_cover_image', 'Error: Cover image required');
    } else {
        $cover_image_data = custom_parse_file($_POST['cover_image_meta'], 'cover_image');
        if (empty($cover_image_data['attachment_id']) and empty($cover_image_data['file'])) {
            return new WP_Error('missing_cover_image', 'Error: Cover image required');
        }
    }

    // State
    if (isset($_POST['state']) and empty($_POST['state'])) {
        return new WP_Error('missing_state', 'Error: State required');
    }

    // URLs
    $is_valid = validate_url_input('website', null);
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_website', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('facebook_url', 'facebook.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_facebook_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('instagram_url', 'instagram.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_instagram_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('tiktok_url', 'tiktok.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_tiktok_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('x_url', 'x.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_x_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('youtube_url', 'youtube.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_youtube_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('bandcamp_url', 'bandcamp.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_bandcamp_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('apple_music_artist_url', 'apple.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_apple_music_artist_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('soundcloud_url', 'soundcloud.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_soundcloud_url', $is_valid->get_error_message());
    }
    $is_valid = validate_url_input('spotify_artist_url', 'spotify.com');
    if ( is_wp_error($is_valid) ) {
        return new WP_Error('invalid_spotify_artist_url', $is_valid->get_error_message());
    }


    return;
}

// Check url is a valid url
// Check url has particular domain if specified
// Check for 404
function validate_url_input($field, $domain = '') {
    if (!isset($_POST[$field]) or empty($_POST[$field])) { return; }

    $url = trim($_POST[$field]);

    // Validate URL syntax
    $is_valid = is_valid_url($url);
    if (is_wp_error($is_valid)) {
        return $is_valid;
    }

    // Validate domain match (if a domain is specified)
    if (!empty($domain)) {
        $parsed_url = parse_url($url, PHP_URL_HOST);
        if (stripos($parsed_url, $domain) === false) {
            return new WP_Error('invalid_domain', "The URL must be from the domain: {$domain}");
        }
    }

    // Check for 404 using HEAD request
    $response = wp_remote_head($url, ['timeout' => 5]);
    if (is_wp_error($response)) {
        return new WP_Error('request_failed', 'Could not reach the URL. Please make sure the site is online.');
    }
    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code === 404) {
        return new WP_Error('url_not_found', 'The URL returned a 404 error (not found). Please check that the link is correct.');
    }

    return true;
}

