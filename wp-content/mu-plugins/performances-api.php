<?php

function get_performance_id() {
    $performance_date = $_GET['performance_date'];
    $performing_act_name = stripslashes($_GET['performing_act_name']);
    $venue_name = stripslashes($_GET['venue_name']);

    $args = array(
        'post_type' => 'performance',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'performance_date',
                'value' => $performance_date,
                'compare' => '=',
                'type' => 'DATE'
            ),
            array(
                'key' => 'performing_act_name',
                'value' => $performing_act_name,
                'compare' => '='
            ),
            array(
                'key' => 'venue_name',
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
    return 0;
}


add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'performances', [
        'methods' => 'GET',
        'callback' => 'get_performance_id',
    ]);
});

function get_performance_by_performance_id() {
    $performance_id = $_GET['performance_id'];
    $args = array(
        'post_type' => 'performance',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'performance_id',
                'value' => $performance_id,
                'compare' => '='
            )
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        return array(
            'id' => get_the_ID(),
            'venue' => get_field('venue'),
            'venue_name' => get_field('venue_name'),
            'performing_act_name' => get_field('performing_act_name'),
            'performance_date' => get_field('performance_date'),
        );
    }
    return 0;
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'performances/id', [
        'methods' => 'GET',
        'callback' => 'get_performance_by_performance_id',
    ]);
});


function get_performances_by_performer() {
    $result = array();
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $performing_act_name = stripslashes($_GET['performing_act_name']);
    $args = array(
        'post_type' => 'performance',
        'nopaging' => true,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'performance_date',
                'value' => $start_date,
                'compare' => '>=',
                'type' => 'DATE'
            ),
            array(
                'key' => 'performance_date',
                'value' => $end_date,
                'compare' => '<',
                'type' => 'DATE'
            ),
            array(
                'key' => 'performing_act_name',
                'value' => $performing_act_name,
                'compare' => '='
            )
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'id' => get_the_ID(),
                'performance_id' => get_field('performance_id'),
                'venue_name' => get_field('venue_name'),
                'performing_act_name' => get_field('performing_act_name'),
                'performance_date' => get_field('performance_date'),
            ));
        }
    }
    return $result;
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'performances/performer', [
        'methods' => 'GET',
        'callback' => 'get_performances_by_performer',
    ]);
});
