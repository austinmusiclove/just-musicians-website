<?php

function jm_location_get_by_zip($zip) {
    global $wpdb;
    $table = jm_get_location_pc_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state, state_code, lat, lng
         FROM {$table}
         WHERE postal_code = %s
         LIMIT 1",
        $zip
    ));
}

function jm_location_get_by_city_state($city, $state_code) {
    global $wpdb;
    $table = jm_get_location_city_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state_code, lat, lng
         FROM {$table}
         WHERE city = %s AND state_code = %s
         LIMIT 1",
        $city, $state_code
    ));
}

function jm_location_search_zips($q, $limit = 20) {
    global $wpdb;
    $table = jm_get_location_pc_table();

    return $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT postal_code
         FROM {$table}
         WHERE postal_code LIKE %s
         ORDER BY postal_code ASC
         LIMIT %d",
        $wpdb->esc_like($q) . '%',
        $limit
    ));
}

function jm_location_search_cities($q, $state_code = null, $limit = 20) {
    global $wpdb;
    $table = jm_get_location_city_table();

    $where  = 'city LIKE %s';
    $params = [$wpdb->esc_like($q) . '%'];

    if ($state_code !== null) {
        $where   .= ' AND state_code = %s';
        $params[] = $state_code;
    }

    $params[] = $limit;

    return $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT city, state_code
         FROM {$table}
         WHERE {$where}
         ORDER BY city ASC
         LIMIT %d",
        $params
    ));
}
