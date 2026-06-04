<?php

if (!defined('ABSPATH')) { exit; }

define('HM_LOCATION_DB_PC_TABLE',   'location_index_pc');
define('HM_LOCATION_DB_CITY_TABLE', 'location_index_city');

require_once __DIR__ . '/location-db/build.php';
require_once __DIR__ . '/location-db/query.php';

function hm_get_location_pc_table() {
    global $wpdb;
    return $wpdb->prefix . HM_LOCATION_DB_PC_TABLE;
}

function hm_get_location_city_table() {
    global $wpdb;
    return $wpdb->prefix . HM_LOCATION_DB_CITY_TABLE;
}

