<?php
/**
 * Plugin Name: Hire More Musicians Messages API
 * Description: A custom plugin to expose REST APIs for refreshing nonce in web applicaiton
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/refresh-nonce', [
        'methods'             => 'GET',
        'callback'            => function () {
            return ['nonce' => wp_create_nonce('wp_rest')];
        },
        'permission_callback' => 'is_user_logged_in',
    ]);
});
