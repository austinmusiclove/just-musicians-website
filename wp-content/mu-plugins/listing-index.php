<?php
/**
 * Plugin Name: Just Musicians Listing Index
 * Description: Builds and maintains a listing index in a custom DB table
 * Version: 1.0
 */

if (!defined('ABSPATH')) { exit; }

define('JM_LISTING_INDEX_TABLE', 'listing_index');

require_once __DIR__ . '/listings-api/listing-index/build.php';
require_once __DIR__ . '/listings-api/listing-index/query.php';

add_action('rest_api_init', function () {
    register_rest_route('listing-index/v1', '/build', [
        'methods'             => 'POST',
        'callback'            => 'jm_build_listing_index',
        'permission_callback' => '__return_true',
        #'permission_callback' => function () { return current_user_can('manage_options'); },
    ]);
});

function jm_get_listing_index_table() {
    global $wpdb;
    return $wpdb->prefix . JM_LISTING_INDEX_TABLE;
}
