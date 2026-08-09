<?php
/**
 * Plugin Name: Hire Musicians Public Listings API
 * Description: Public JSON API for discovering live music listings, intended for LLMs
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once __DIR__ . '/public-listings-api/handle-musicians-live-music.php';

// Register the public API route and flush rewrite rules once
add_action('init', function () {
    add_rewrite_rule(
        '^api/v1/musicians/live-music/?$',
        'index.php?public-api=musicians-live-music',
        'top'
    );
});

// Register the public API query var
add_filter('query_vars', function ($vars) {
    $vars[] = 'public-api';
    return $vars;
});

// Dispatch public API requests
add_action('template_redirect', function () {
    if (get_query_var('public-api') === 'musicians-live-music') {
        hm_public_api_handle_musicians_live_music();
    }
});
