<?php

function get_venue_reviews() {
    $venueId = $_GET['venue_id'];
    $result = array();
    //$pay_metric = '';
    //$pay_type = '';
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => 'venue',
                'value' => $venueId,
                'compare' => '=='
            )
        )
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'overall_rating' => get_field('overall_rating'),
                'comp_types_string' => get_field('_comp_types_string'),
                'hours_performed' => get_field('hours_performed'),
                'total_performers' => get_field('total_performers'),
                'has_guarantee_comp' => get_field('_has_guarantee_comp'),
                'guarantee_earnings' => get_field('guarantee_earnings'),
                'has_door_comp' => get_field('_has_door_comp'),
                'door_earnings' => get_field('door_earnings'),
                'door_percentage' => get_field('door_percentage'),
                'has_sales_comp' => get_field('_has_sales_comp'),
                'sales_earnings' => get_field('sales_earnings'),
                'sales_percentage' => get_field('sales_percentage'),
                'has_tips_comp' => get_field('_has_tips_comp'),
                'tips_earnings' => get_field('tips_earnings'),
                'total_earnings' => get_field('total_earnings'),
                'payment_speed' => get_field('payment_speed'),
                'payment_method' => get_field('payment_method'),
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
