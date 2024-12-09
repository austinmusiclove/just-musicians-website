<?php

function get_venues() {
    $min_review_count = (isset($_GET['min_review_count'])) ? stripslashes($_GET['min_review_count']) : 1;
    $result = array();
    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => '_stats_review_count',
                'value' => $min_review_count,
                'compare' => '>='
            )
        ),
        'order' => 'DEC',
        'orderby' => 'meta_value_num',
        'meta_key' => '_average_earnings'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'name' => get_field('name'),
                'latitude' => get_field('latitude'),
                'longitude' => get_field('longitude'),
                'average_earnings' => get_field('_average_earnings'),
                'review_count' => get_field('_review_count'),
                'overall_rating' => get_field('_overall_rating'),
                'permalink' => get_the_permalink(),
            ));
        }
    }
    return $result;
}

function get_venue_post_id_by_name() {
    $venue_name = stripslashes($_GET['venue_name']);

    $args = array(
        'post_type' => 'venue',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'name',
                'value' => $venue_name,
                'compare' => '='
            )
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        return get_the_ID();
    }
}
function get_venues_by_post_id_batch() {
    $result = array();
    $venue_ids = $_GET['venue_ids'];
    $venue_ids_array =  array_filter(explode(',', $venue_ids));
    if (count($venue_ids_array) == 0) {return $result;}

    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
        'post_status' => 'publish',
        'post__in' => $venue_ids_array,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'name' => get_field('name'),
            ));
        }
    }
    return $result;
}

add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venues', [
        'methods' => 'GET',
        'callback' => 'get_venues',
    ]);
});
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venues/id', [
        'methods' => 'GET',
        'callback' => 'get_venue_post_id_by_name',
    ]);
});
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venues/batch', [
        'methods' => 'GET',
        'callback' => 'get_venues_by_post_id_batch',
    ]);
});
