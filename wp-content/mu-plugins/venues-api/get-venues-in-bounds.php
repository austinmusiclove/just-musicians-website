<?php

function get_venues_in_bounds() {
    // Expect: ?n=...&s=...&e=...&w=...
    $north = isset($_GET['n']) ? floatval($_GET['n']) : null;
    $south = isset($_GET['s']) ? floatval($_GET['s']) : null;
    $east  = isset($_GET['e']) ? floatval($_GET['e']) : null;
    $west  = isset($_GET['w']) ? floatval($_GET['w']) : null;

    if ($north === null || $south === null || $east === null || $west === null) {
        return [];
    }

    /**
     * Longitude edge case:
     * IF the map crosses the 180° meridian, example:
     * west = 170, east = -170
     * then we must do OR instead of AND.
     */
    $crosses_antimeridian = ($west > $east);

    // Base query
    $meta_query = [
        'relation' => 'AND',
        [
            'key' => 'latitude',
            'value' => [$south, $north],
            'type' => 'DECIMAL(10,8)',
            'compare' => 'BETWEEN'
        ]
    ];

    if ($crosses_antimeridian) {
        // longitude >= west OR longitude <= east
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key'     => 'longitude',
                'value'   => $west,
                'type'    => 'DECIMAL(10,8)',
                'compare' => '>='
            ],
            [
                'key'     => 'longitude',
                'value'   => $east,
                'type'    => 'DECIMAL(10,8)',
                'compare' => '<='
            ]
        ];
    } else {
        // Normal case: longitude BETWEEN west and east
        $meta_query[] = [
            'key' => 'longitude',
            'value' => [$west, $east],
            'type' => 'DECIMAL(10,8)',
            'compare' => 'BETWEEN'
        ];
    }

    // Query posts
    $query = new WP_Query([
        'post_type'      => 'venue',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => $meta_query
    ]);

    // Format results
    $venues = [];
    foreach ($query->posts as $post) {
        $venues[] = [
            'id'        => $post->ID,
            'name'      => get_post_meta($post->ID, 'name', true),
            'latitude'  => floatval(get_post_meta($post->ID, 'latitude', true)),
            'longitude' => floatval(get_post_meta($post->ID, 'longitude', true)),
            'permalink' => get_permalink($post->ID)
        ];
    }

    return $venues;
}

