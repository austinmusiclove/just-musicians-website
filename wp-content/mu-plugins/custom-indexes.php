<?php
/**
 * Plugin Name: Just Musicians Custom Indexes
 * Description: Builds and maintains custom database indexes
 * Version: 1.0
 */

if (!defined('ABSPATH')) { exit; }

define('HM_LOCATION_DB_PC_TABLE', 'location_index_pc');
define('HM_LOCATION_DB_CITY_TABLE', 'location_index_city');
define('HM_LISTING_INDEX_TABLE', 'listing_index');
define('HM_PROPOSAL_INDEX_TABLE', 'proposal_index');
define('HM_NOTIFICATIONS_TABLE', 'notifications');

require_once __DIR__ . '/custom-indexes/location/build.php';
require_once __DIR__ . '/custom-indexes/location/query.php';

require_once __DIR__ . '/custom-indexes/listings/build.php';
require_once __DIR__ . '/custom-indexes/listings/query.php';
require_once __DIR__ . '/custom-indexes/listings/update.php';

require_once __DIR__ . '/custom-indexes/proposals/build.php';
require_once __DIR__ . '/custom-indexes/proposals/query.php';
require_once __DIR__ . '/custom-indexes/proposals/update.php';

require_once __DIR__ . '/custom-indexes/notifications/build.php';
require_once __DIR__ . '/custom-indexes/notifications/query.php';

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

function hm_get_proposal_index_table() {
    global $wpdb;
    return $wpdb->prefix . HM_PROPOSAL_INDEX_TABLE;
}

function hm_get_notifications_table() {
    global $wpdb;
    return $wpdb->prefix . HM_NOTIFICATIONS_TABLE;
}
