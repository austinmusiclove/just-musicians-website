<?php

function get_venue_reviews() {
    $venueId = $_GET['venue_id'];
    $result = array();
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => 'venue',
                'value' => $venueId,
                'compare' => 'LIKE'
            )
        )
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'overall_rating' => get_field('overall_rating'),
                'comp_types_string' => get_field('_comp_types_string'),
                'hours_performed' => get_field('hours_performed'),
                'total_performers' => get_field('total_performers'),
                'has_guarantee_comp' => in_array('Guarantee', get_field('comp_structure')),
                'guarantee_earnings' => get_field('guarantee_earnings'),
                'has_door_comp' => in_array('Door', get_field('comp_structure')),
                'door_earnings' => get_field('door_earnings'),
                'door_percentage' => get_field('door_percentage'),
                'has_bar_comp' => in_array('Bar', get_field('comp_structure')),
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


add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venue_reviews', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews',
    ]);
});
