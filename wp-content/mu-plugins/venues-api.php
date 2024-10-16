<?php

function get_venues() {
    $result = array();
    //$pay_metric = '';
    //$pay_type = '';
    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => '_review_count',
                'value' => 0,
                'compare' => '>'
            )
        ),
        'order' => 'DEC',
        'orderby' => 'meta_value_num',
        'meta_key' => '_average_pay'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
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

function get_top_paying_venues() {
    $result = array();
    $args = array(
        'post_type' => 'venue',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => '_review_count',
                'value' => 0,
                'compare' => '>'
            )
        ),
        'order' => 'DEC',
        'orderby' => 'meta_value_num',
        'meta_key' => '_average_pay'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'name' => get_field('name'),
                'average_pay' => get_field('_average_pay'),
                'review_count' => get_field('_review_count'),
                'overall_rating' => get_field('_overall_rating'),
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
 register_rest_route( 'venues/v1', 'top', [
    'methods' => 'GET',
    'callback' => 'get_top_paying_venues',
  ]);
});

