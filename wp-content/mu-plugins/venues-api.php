<?php
/**
 * Plugin Name: Just Musicians Venues API
 * Description: A custom plugin to expose REST APIs for managing venue posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venues', [
        'methods' => 'GET',
        'callback' => 'get_venues',
    ]);
    register_rest_route( 'v1', 'venues/id', [
        'methods' => 'GET',
        'callback' => 'get_venue_post_id_by_name',
    ]);
    register_rest_route( 'v1', 'venues/batch', [
        'methods' => 'GET',
        'callback' => 'get_venues_by_post_id_batch',
    ]);
    register_rest_route( 'venues/v1', 'stats', [
        'methods' => 'GET',
        'callback' => 'update_venue_stats',
    ]);
});


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

function update_venue_stats() {
    // get venues
    $args = array(
        'post_type' => 'venue',
        'nopaging' => true,
    );
    $venues_query = new WP_Query($args);
    if ($venues_query->have_posts()) {

        // for each venue get reviews and generate stats
        while( $venues_query->have_posts() ) {
            $review_count = 0;
            $stats_review_count = 0;
            $rating_count = 0;
            $overall_rating_sum = 0;
            $earnings_sum = 0;

            // Get reviews for this venue
            $venues_query->the_post();
            $venue_post_id = get_the_ID();
            $args = array(
                'post_type' => 'venue_review',
                'nopaging' => true,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'venue_post_id',
                        'value' => $venue_post_id,
                        'compare' => '='
                    )
                )
            );
            $venue_reviews_query = new WP_Query($args);
            if ($venue_reviews_query->have_posts()) {

                while( $venue_reviews_query->have_posts() ) {
                    $venue_reviews_query->the_post();
                    $review_count++;
                    if (!get_field('exclude_from_stats')) {
                        $stats_review_count++;
                        $earnings_sum += (float)get_field('total_earnings');
                    }
                    $overall_rating = (int)get_field('overall_rating');
                    if ($overall_rating > 0) {
                        $rating_count ++;
                        $overall_rating_sum += $overall_rating;
                    }
                }
            }

            // Calc averages
            $overall_rating_average = round(($rating_count > 0) ? $overall_rating_sum/$rating_count : 0, 2);
            $earnings_average = round(($stats_review_count > 0) ? $earnings_sum/$stats_review_count : 0, 2);

            // update venue meta data
            $update_args = array(
                'ID' => $venue_post_id,
                'meta_input' => array(
                    '_review_count' => $review_count,
                    '_stats_review_count' => $stats_review_count,
                    '_overall_rating' => $overall_rating_average,
                    '_average_earnings' => $earnings_average,
                ),
            );
            $update_result = wp_update_post( wp_slash($update_args), true );
            if( is_wp_error( $update_result ) ) {
                return $update_result->get_error_message();
            }
        }
    }
    return;
}

