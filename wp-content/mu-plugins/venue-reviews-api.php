<?php
/**
 * Plugin Name: Hire More Musicians Performances API
 * Description: A custom plugin to expose REST APIs for managing venue_review posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'venue_reviews', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews',
    ]);
    register_rest_route( 'v1', 'venue_reviews/batch', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews_batch',
    ]);
    register_rest_route( 'v1', 'venue_reviews/csv', [
        'methods' => 'GET',
        'callback' => 'get_venue_reviews_csv',
    ]);
    register_rest_route( 'v1', 'venue_review/stats', [
        'methods' => 'GET',
        'callback' => 'update_venue_review_stats',
    ]);
});

function get_venue_reviews() {
    $venue_id = sanitize_text_field($_GET['venue_id']);
    $result = array();
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'post_status' => 'publish',
        'meta_key' => 'venue_post_id',
        'meta_value' => $venue_id,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'venue_post_id' => get_field('venue_post_id'),
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
                'exclude_from_stats' => get_field('exclude_from_stats'),
            ));
        }
    }
    wp_reset_postdata();
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
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'venue_post_id',
                'value' => explode(',', $venue_ids),
                'compare' => 'IN'
            )
        )
    );

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'venue_post_id' => get_field('venue_post_id'),
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
                'exclude_from_stats' => get_field('exclude_from_stats'),
            ));
        }
    }
    wp_reset_postdata();
    return $result;
}

function get_venue_reviews_csv() {
    $result = "id,Venue Post Id,Overall Rating,Hours Performed,Total Performers,Comp Structure String,Guarantee Promise,Guarantee Eearnings,Door Earnings,Door Percentage,Bar Earnings,Bar Percentage,Tips Earnings,Total Earnings,Payment Speed,Payment Method,Backline,Review,end\n";
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
        'post_status' => 'publish',
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            $row = array(
                get_the_ID(),
                get_field('venue_post_id'),
                get_field('overall_rating'),
                get_field('hours_performed'),
                get_field('total_performers'),
                get_field('_comp_structure_string'),
                get_field('guarantee_promise'),
                get_field('guarantee_earnings'),
                get_field('door_earnings'),
                get_field('door_percentage'),
                get_field('bar_earnings'),
                get_field('bar_percentage'),
                get_field('tips_earnings'),
                get_field('total_earnings'),
                get_field('payment_speed'),
                get_field('payment_method'),
                get_field('backline'),
                get_field('review'),
                get_field('exclude_from_stats'),
                "end",
            );
            $result = $result . implode(',', $row) . "\n";
        }
    }
    wp_reset_postdata();
    return $result;
}

function update_venue_review_stats() {
    // get venues
    $args = array(
        'post_type' => 'venue_review',
        'nopaging' => true,
    );
    $venues_query = new WP_Query($args);
    if ($venues_query->have_posts()) {

        // for each venue review, update stats
        while( $venues_query->have_posts() ) {
            $venues_query->the_post();
            $venue = get_field('venue');
            $venue_review_post_id = get_the_ID();
            $total_earnings = (float)get_post_meta(get_the_ID(), 'total_earnings' , true);
            $total_performers = (int)get_post_meta(get_the_ID(), 'total_performers' , true);
            $hours_performed = (float)get_post_meta(get_the_ID(), 'hours_performed' , true);
            $earnings_per_performer = round(($total_performers > 0) ? $total_earnings / $total_performers : 0, 2);
            $earnings_per_hour = round(($hours_performed > 0) ? $total_earnings / $hours_performed : 0, 2);
            $earnings_per_performer_per_hour = round(($total_performers > 0 && $hours_performed > 0) ? $total_earnings / $hours_performed / $total_performers : 0, 2);

            // update venue review meta data
            $update_args = array(
                'ID' => $venue_review_post_id,
                'meta_input' => array(
                    'venue_name' => $venue[0]->name,
                    'venue_post_id' => $venue[0]->ID,
                    '_earnings_per_performer' => $earnings_per_performer,
                    '_earnings_per_hour' => $earnings_per_hour,
                    '_earnings_per_performer_per_hour' => $earnings_per_performer_per_hour,
                ),
            );
            $update_result = wp_update_post( wp_slash($update_args), true );
            if( is_wp_error( $update_result ) ) {
                wp_reset_postdata();
                return $venue_post_id->get_error_message();
            }
        }
    }
    wp_reset_postdata();
    return;
}

