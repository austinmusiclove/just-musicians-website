<?php

if (!defined('ABSPATH')) { exit; }

define('JM_LOCATION_DB_PC_TABLE',   'location_index_pc');
define('JM_LOCATION_DB_CITY_TABLE', 'location_index_city');

require_once __DIR__ . '/location-db/build.php';
require_once __DIR__ . '/location-db/query.php';

function jm_get_location_pc_table() {
    global $wpdb;
    return $wpdb->prefix . JM_LOCATION_DB_PC_TABLE;
}

function jm_get_location_city_table() {
    global $wpdb;
    return $wpdb->prefix . JM_LOCATION_DB_CITY_TABLE;
}
