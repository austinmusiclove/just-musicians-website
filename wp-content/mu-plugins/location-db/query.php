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

function jm_location_search_pc($q, $limit = 40) {
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

function jm_location_search_cities($q, $limit = 40) {
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

function jm_location_search($q, $limit = 40) {
    $pcs = jm_location_search_pc($q, $limit);
    $cities = jm_location_search_cities($q, $limit);

    $results = [];

    foreach ($pcs as $pc) {
        $results[] = [
            'label'       => $pc->city . ', ' . $pc->state . ' ' . $pc->postal_code,
            'value'       => $pc->postal_code,
            'type'        => 'postal_code',
            'city'        => $pc->city,
            'state'       => $pc->state,
            'lat'         => $pc->lat,
            'lng'         => $pc->lng,
        ];
    }

    foreach ($cities as $city) {
        $results[] = [
            'label'       => $city->city . ', ' . $city->state,
            'value'       => $city->city . ', ' . $city->state,
            'type'        => 'city',
            'city'        => $city->city,
            'state'       => $city->state,
            'lat'         => $city->lat,
            'lng'         => $city->lng,
        ];
    }

    return $results;
}

