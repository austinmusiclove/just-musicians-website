<?php
/**
 * Plugin Name: Just Musicians Custom Indexes
 * Description: Builds and maintains custom database indexes (listings, location, etc.)
 * Version: 1.0
 */

if (!defined('ABSPATH')) { exit; }

define('HM_LISTING_INDEX_TABLE', 'listing_index');
define('HM_LOCATION_DB_PC_TABLE', 'location_index_pc');
define('HM_LOCATION_DB_CITY_TABLE', 'location_index_city');

require_once __DIR__ . '/custom-indexes/listings/build.php';
require_once __DIR__ . '/custom-indexes/listings/query.php';
require_once __DIR__ . '/custom-indexes/listings/update.php';

require_once __DIR__ . '/custom-indexes/location/build.php';
require_once __DIR__ . '/custom-indexes/location/query.php';

function hm_get_listing_index_table() {
    global $wpdb;
    return $wpdb->prefix . HM_LISTING_INDEX_TABLE;
}

function hm_get_location_pc_table() {
    global $wpdb;
    return $wpdb->prefix . HM_LOCATION_DB_PC_TABLE;
}

function hm_get_location_city_table() {
    global $wpdb;
    return $wpdb->prefix . HM_LOCATION_DB_CITY_TABLE;
}
