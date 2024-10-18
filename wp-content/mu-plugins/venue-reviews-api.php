<?php

function get_venue_reviews(WP_REST_Request $request) {
    $params = $request.get_query_params();
    $venueId = $params['ID'];
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
            ));
        }
    }
    return $result;
}


add_action('rest_api_init', function () {
 register_rest_route( 'v1', 'venues_reviews', [
    'methods' => 'GET',
    'callback' => 'get_venue_reviews',
  ]);
});
