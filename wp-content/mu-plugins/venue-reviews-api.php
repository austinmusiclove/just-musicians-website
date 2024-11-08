<?php

function get_venue_reviews() {
    $venue_id = sanitize_text_field($_GET['venue_id']);
    $result = array();
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => 'venue',
                'value' => $venue_id,
                'compare' => 'IN',
                'type' => 'NUMERIC'
            )
        )
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'venue' => get_field('venue'),
                'overall_rating' => get_field('overall_rating'),
                'comp_types_string' => get_field('_comp_types_string'),
                'hours_performed' => get_field('hours_performed'),
                'total_performers' => get_field('total_performers'),
                'comp_structure' => get_field('comp_structure'),
                'comp_structure_string' => get_field('_comp_structure_string'),
                'is_versus' => in_array('Versus', get_field('comp_structure')),
                'has_guarantee_comp' => in_array('Guarantee', get_field('comp_structure')) || get_field('versus_comp_1') == 'Guarantee' || get_field('versus_comp_2') == 'Guarantee',
                'guarantee_promise' => get_field('guarantee_promise'),
                'guarantee_earnings' => get_field('guarantee_earnings'),
                'has_door_comp' => in_array('Door Deal', get_field('comp_structure')) || get_field('versus_comp_1') == 'Door Deal' || get_field('versus_comp_2') == 'Door Deal',
                'door_earnings' => get_field('door_earnings'),
                'door_percentage' => get_field('door_percentage'),
                'has_bar_comp' => in_array('Bar Deal', get_field('comp_structure')) || get_field('versus_comp_1') == 'Bar Deal' || get_field('versus_comp_2') == 'Bar Deal',
                'bar_earnings' => get_field('bar_earnings'),
                'bar_percentage' => get_field('bar_percentage'),
                'has_tips_comp' => in_array('Tips', get_field('comp_structure')),
                'tips_earnings' => get_field('tips_earnings'),
                'total_earnings' => get_field('total_earnings'),
                'payment_speed' => get_field('payment_speed'),
                'payment_method' => get_field('payment_method'),
                'backline' => get_field('backline'),
                'review' => get_field('review'),
            ));
        }
    }
    return $result;
}

function get_venue_reviews_batch() {
    $result = array();
    $venue_ids = $_GET['venue_ids'];
    $venue_ids_array =  array_filter(explode(',', $venue_ids));
    if (count($venue_ids_array) == 0) {return $result;}

    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'meta_query' => array(
            'relation' => 'OR'
        )
    );
    $meta_query = array('relation' => 'OR');
    foreach ($venue_ids_array as $venue_id) {
        array_push($args['meta_query'], array(
            'key' => 'venue',
            'value' => $venue_id,
            'compare' => 'IN'
        ));
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'venue' => get_field('venue')[0],
                'overall_rating' => get_field('overall_rating'),
                'comp_types_string' => get_field('_comp_types_string'),
                'hours_performed' => get_field('hours_performed'),
                'total_performers' => get_field('total_performers'),
                'comp_structure' => get_field('comp_structure'),
                'comp_structure_string' => get_field('_comp_structure_string'),
                'guarantee_promise' => get_field('guarantee_promise'),
                'guarantee_earnings' => get_field('guarantee_earnings'),
                'door_earnings' => get_field('door_earnings'),
                'door_percentage' => get_field('door_percentage'),
                'bar_earnings' => get_field('bar_earnings'),
                'bar_percentage' => get_field('bar_percentage'),
                'tips_earnings' => get_field('tips_earnings'),
                'total_earnings' => get_field('total_earnings'),
                'earnings_per_performer' => get_field('_earnings_per_performer'),
                'earnings_per_hour' => get_field('_earnings_per_hour'),
                'earnings_per_performer_per_hour' => get_field('_earnings_per_performer_per_hour'),
                'payment_speed' => get_field('payment_speed'),
                'payment_method' => get_field('payment_method'),
                'backline' => get_field('backline'),
                'review' => get_field('review'),
            ));
        }
    }
    return $result;
}

add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venue_reviews', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews',
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venue_reviews/batch', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews_batch',
    ]);
});
