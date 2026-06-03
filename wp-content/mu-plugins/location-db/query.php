<?php

function jm_location_get_by_pc($pc) {
    global $wpdb;
    $table = jm_get_location_pc_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state, state_code, country, lat, lng
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

function jm_location_search($q, $get_cities = true, $get_pc = true, $limit = 40) {

    $results = [];

    if ($get_pc) {
        $pcs = jm_location_search_pc($q, $limit);
        foreach ($pcs as $pc) {
            $results[] = [
                'label'       => $pc->city . ', ' . $pc->state . ' ' . $pc->postal_code,
                'value'       => $pc->postal_code,
                'type'        => 'postal_code',
                'city'        => $pc->city,
                'state'       => $pc->state,
                'postal_code' => $pc->postal_code,
                'country'     => $pc->country,
                'lat'         => $pc->lat,
                'lng'         => $pc->lng,
            ];
        }
    }

    if ($get_cities) {
        $cities = jm_location_search_cities($q, $limit);
        foreach ($cities as $city) {
            $results[] = [
                'label'       => $city->city . ', ' . $city->state,
                'value'       => $city->city . ', ' . $city->state,
                'type'        => 'city',
                'city'        => $city->city,
                'state'       => $city->state,
                'country'     => $city->country,
                'lat'         => $city->lat,
                'lng'         => $city->lng,
            ];
        }
    }

    return $results;
}

