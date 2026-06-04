<?php

function hm_location_get_by_pc($pc) {
    global $wpdb;
    $table = hm_get_location_pc_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT city, state, state_code, country, lat, lng
         FROM {$table}
         WHERE postal_code = %s
         LIMIT 1",
        $pc
    ));
}

function hm_location_search_pc($q, $limit = 40) {
    global $wpdb;
    $table = hm_get_location_pc_table();

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

function hm_location_search_cities($q, $limit = 40) {
    global $wpdb;
    $table = hm_get_location_city_table();

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

function hm_location_search($q, $get_cities = true, $get_pc = true, $limit = 40) {

    $results = [];

    if ($get_pc) {
        $pcs = hm_location_search_pc($q, $limit);
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
        $cities = hm_location_search_cities($q, $limit);
        foreach ($cities as $city) {
            $results[] = [
                'label'       => $city->city . ', ' . $city->state,
                'value'       => $city->city . ', ' . $city->state,
                'type'        => 'city',
                'city'        => $city->city,
                'state'       => $city->state,
                'postal_code' => '',
                'country'     => $city->country,
                'lat'         => $city->lat,
                'lng'         => $city->lng,
            ];
        }
    }

    return $results;
}

function hm_get_ip_location() {
    //$ip = '136.50.121.27'; // San Antonio
    //$ip = '173.174.46.198'; // Austin
    //$ip = '159.26.101.17'; // Boston
    //$ip = '159.26.106.136'; // London
    //$ip = '88.178.237.84'; // France (no vpn)
    //$ip = '136.50.121.27'; // San Antonio
    //$ip = '207.81.221.222'; // Vancouver
    //$ip = '192.206.151.131'; // Toronto (does not get city or province)
    //$ip = '172.3.173.227'; // Austell, GA
    $ip = $_SERVER['REMOTE_ADDR'];
    $db_path = __DIR__ . '/data/GeoLite2-City.mmdb';

    if (!file_exists($db_path)) return null;

    require_once __DIR__ . '/data/geoip2.phar';

    try {
        $reader = new GeoIp2\Database\Reader($db_path);
        $record = $reader->city($ip);
        $country = $record->country->isoCode;
        $lat = $record->location->latitude;
        $lng = $record->location->longitude;
        $city = $record->city->name;

        if (!in_array($country, ['US', 'CA'], true)) return null;
        if (empty($lat) || empty($lng) || empty($city)) return null;

        return (object) [
            'lat'     => $lat,
            'lon'     => $lng,
            'city'    => $city,
            'region'  => $record->mostSpecificSubdivision->name,
            'country' => $country,
        ];
    } catch (\Exception $e) {
        return null;
    }
}


