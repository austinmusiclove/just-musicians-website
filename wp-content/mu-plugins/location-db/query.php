<?php

function jm_location_get_by_pc($pc) {
    global $wpdb;
    $table = jm_get_location_pc_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state, state_code, lat, lng
         FROM {$table}
         WHERE postal_code = %s
         LIMIT 1",
        $pc
    ));
}

function jm_location_search_pc($q, $limit = 20) {
    global $wpdb;
    $table = jm_get_location_pc_table();

    return $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT postal_code, city, state, country, lat, lng
         FROM {$table}
         WHERE postal_code LIKE %s
         ORDER BY postal_code ASC
         LIMIT %d",
        $wpdb->esc_like($q) . '%',
        $limit
    ));
}

function jm_location_search_cities($q, $limit = 20) {
    global $wpdb;
    $table = jm_get_location_city_table();

    return $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT city, state, country, lat, lng
         FROM {$table}
         WHERE city LIKE %s
         ORDER BY city ASC
         LIMIT %d",
        $wpdb->esc_like($q) . '%',
        $limit
    ));
}

function jm_location_get_by_city_state_country($city, $state_code, $country) {
    global $wpdb;
    $table = jm_get_location_city_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state_code, state, country, lat, lng
         FROM {$table}
         WHERE city = %s AND state_code = %s AND country = %s
         LIMIT 1",
        $city, $state_code, $country
    ));
}
