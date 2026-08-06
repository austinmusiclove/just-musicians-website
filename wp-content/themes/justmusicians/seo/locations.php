<?php

function get_seo_locations() {
    return [
        'austin-tx' => ['city' => 'Austin', 'state' => 'Texas', 'lat' => 30.2672, 'lng' => -97.7431],
    ];
}

function get_seo_location($slug) {
    $locations = get_seo_locations();
    return isset($locations[$slug]) ? $locations[$slug] : null;
}

