<?php
/**
 * Plugin Name: Hire More Musicians Venues API
 * Description: A custom plugin to expose REST APIs for managing venue posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'venues-api/get-venues-in-bounds.php';
require_once 'venues-api/update-venue-stats.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venues', [
        'methods' => 'GET',
        'callback' => 'get_venues',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route( 'v1', 'venues/id', [
        'methods' => 'GET',
        'callback' => 'get_venue_post_id_by_name',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route( 'v1', 'venues/batch', [
        'methods' => 'GET',
        'callback' => 'get_venues_by_post_id_batch',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route( 'venues/v1', 'stats', [
        'methods' => 'PUT',
        'callback' => 'update_all_venue_stats',
        'permission_callback' => function () { return current_user_can('manage_options'); },
    ]);
    register_rest_route( 'venues/v1', 'bounds', [
        'methods' => 'GET',
        'callback' => 'get_venues_in_bounds',
        'permission_callback' => '__return_true',
    ]);
});


function get_venues($args) {
    $name_search_term = (!empty($args['name_search']))     ? sanitize_text_field($args['name_search']) : null;
    $min_review_count = (isset($_GET['min_review_count'])) ? stripslashes($_GET['min_review_count'])   : null;
    $result = array();
    $query_args = array(
        'post_type' => 'venue',
        'nopaging' => true,
    );
    $meta_queries = [];
    if (!empty($min_review_count)) {
        $meta_queries[] = [
            'key' => '_stats_review_count',
            'value' => $min_review_count,
            'compare' => '>=',
        ];
        $query_args['order']    = 'DEC';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['meta_key'] = '_average_earnings';
    }
    if (!empty($name_search_term)) {
        $meta_queries[] = [
            'key' => 'name',
            'value' => $name_search_term,
            'compare' => 'LIKE',
        ];
        $query_args['orderby'] = 'relevance';
    }
    $query_args['meta_query'] = count($meta_queries) == 0 ? null : (count($meta_queries) == 1 ? [...$meta_queries] : [
        'relation' => 'AND',
        ...$meta_queries,
    ]);
    $query = new WP_Query($query_args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID'               => get_the_ID(),
                'name'             => get_field('name'),
                'latitude'         => get_field('latitude'),
                'longitude'        => get_field('longitude'),
                'street_address'   => get_field('street_address'),
                'address_locality' => get_field('address_locality'),
                'postal_code'      => get_field('postal_code'),
                'address_region'   => get_field('address_region'),
                'average_earnings' => get_field('_average_earnings'),
                'review_count'     => get_field('_review_count'),
                'overall_rating'   => get_field('_overall_rating'),
                'permalink'        => get_the_permalink(),
            ));
        }
    }
    wp_reset_postdata();
    return $result;
}

function get_venue_post_id_by_name() {
    $result = 0;
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
        $result = get_the_ID();
    }
    wp_reset_postdata();
    return $result;
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
    wp_reset_postdata();
    return $result;
}

function update_all_venue_stats() {
    // get venues
    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
    );
    $venues_query = new WP_Query($args);
    if ($venues_query->have_posts()) {

        // for each venue get reviews and generate stats
        while( $venues_query->have_posts() ) {
            $venues_query->the_post();
            $venue_post_id = get_the_ID();
            update_venue_stats($venue_post_id);
        }
    }
    wp_reset_postdata();
    return;
}
