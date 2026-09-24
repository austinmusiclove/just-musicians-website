<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action('rest_api_init', function () {
    register_rest_route('v1', 'detect-location', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'detect_ip_location',
        'permission_callback' => '__return_true',
    ));
});

function detect_ip_location() {
    $lat            = null;
    $lng            = null;
    $location_label = null;

    $detected_location = hm_get_ip_location();
    if ($detected_location) {
        $lat = $detected_location->lat;
        $lng = $detected_location->lon;
        $location_label = "{$detected_location->city}, {$detected_location->region}";
    } else {
        $lat = 30.2672;
        $lng = -97.7431;
        $location_label = 'Austin, Texas';
    }

    wp_send_json([
        'lat'   => (float)$lat,
        'lng'   => (float)$lng,
        'label' => $location_label,
    ]);
}
