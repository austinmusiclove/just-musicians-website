<?php

if (!defined('ABSPATH')) { exit; }

define('JM_LOCATION_DB_TABLE', 'location_index');

require_once __DIR__ . '/location-db/build.php';
require_once __DIR__ . '/location-db/query.php';

function jm_get_location_table() {
    global $wpdb;
    return $wpdb->prefix . JM_LOCATION_DB_TABLE;
}
