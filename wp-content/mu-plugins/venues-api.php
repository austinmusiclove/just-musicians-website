<?php

function get_venues() {
    $result = array();

    $pay_metric = $_GET['pay_metric'];
    $pay_type = $_GET['pay_type'];
    $review_count_var = '_review_count';
    $sort_var = '_average_earnings';
    if (isset($pay_metric)) {
        $sort_var = '_' . $pay_metric;
    }
    if (isset($pay_type)) {
        $review_count_var = '_' . $pay_type . $review_count_var;
        if (isset($pay_metric)) {
            $sort_var = '_' . $pay_type . $sort_var;
        }
    }

    $min_review_count = 1;
    if (isset($_GET['min_review_count'])) {
        $min_review_count = $_GET['min_review_count'];
    }

    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => $review_count_var,
                'value' => $min_review_count,
                'compare' => '>='
            )
        ),
        'order' => 'DEC',
        'orderby' => 'meta_value_num',
        'meta_key' => $sort_var
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
                'pay_metric' => get_field($sort_var),
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
