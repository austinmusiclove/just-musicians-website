<?php

/**
 *
 * @link              justmusicians.org
 * @since             1.0.0
 * @package           Venue Stats
 *
 * @wordpress-plugin
 * Plugin Name:       Meta Post API
 * Plugin URI:        justmusicians.org
 * Description:       Agregates data from venue reviews and stores insights in venue posts
 * Version:           1.0.0
 * Author:            Just Musicians
 * Author URI:        justmusicians.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       venue-stats
 * Domain Path:       /languages
 */

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
            $guarantee_review_count = 0;
            $door_deal_review_count = 0;
            $bar_deal_review_count = 0;
            $overall_rating_sum = 0;
            $earnings_sum = 0;
            $earnings_per_performer_sum = 0;
            $earnings_per_hour_sum = 0;
            $earnings_per_performer_per_hour_sum = 0;
            $guarantee_earnings_sum = 0;
            $guarantee_earnings_per_performer_sum = 0;
            $guarantee_earnings_per_hour_sum = 0;
            $guarantee_earnings_per_performer_per_hour_sum = 0;
            $door_deal_earnings_sum = 0;
            $door_deal_earnings_per_performer_sum = 0;
            $door_deal_earnings_per_hour_sum = 0;
            $door_deal_earnings_per_performer_per_hour_sum = 0;
            $bar_deal_earnings_sum = 0;
            $bar_deal_earnings_per_performer_sum = 0;
            $bar_deal_earnings_per_hour_sum = 0;
            $bar_deal_earnings_per_performer_per_hour_sum = 0;

            // Get reviews for this venue
            $venues_query->the_post();
            $venue_post_id = get_the_ID();
            $args = array(
                'post_type' => 'venue_review',
                'nopaging' => true,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'venue',
                        'value' => '"' . $venue_post_id . '"',
                        'compare' => 'LIKE'
                    )
                )
            );
            $venue_reviews_query = new WP_Query($args);
            if ($venue_reviews_query->have_posts()) {

                while( $venue_reviews_query->have_posts() ) {
                    $venue_reviews_query->the_post();
                    $review_count++;
                    $overall_rating_sum += (int)get_field('overall_rating');
                    $review_earnings = (float)get_field('total_earnings');
                    $review_earnings_per_performer = (float)get_field('_earnings_per_performer');
                    $review_earnings_per_hour = (float)get_field('_earnings_per_hour');
                    $review_earnings_per_performer_per_hour = (float)get_field('_earnings_per_performer_per_hour');

                    $earnings_sum += $review_earnings;
                    $earnings_per_performer_sum += $review_earnings_per_performer;
                    $earnings_per_hour_sum += $review_earnings_per_hour;
                    $earnings_per_performer_per_hour_sum += $review_earnings_per_performer_per_hour;
                    if ('Guarantee' == get_field('_comp_structure_string')) {
                        $guarantee_review_count++;
                        $guarantee_earnings_sum += $review_earnings;
                        $guarantee_earnings_per_performer_sum += $review_earnings_per_performer;
                        $guarantee_earnings_per_hour_sum += $review_earnings_per_hour;
                        $guarantee_earnings_per_performer_per_hour_sum += $review_earnings_per_performer_per_hour;
                    }
                    if ('Door Deal' == get_field('_comp_structure_string')) {
                        $door_deal_review_count++;
                        $door_deal_earnings_sum += $review_earnings;
                        $door_deal_earnings_per_performer_sum += $review_earnings_per_performer;
                        $door_deal_earnings_per_hour_sum += $review_earnings_per_hour;
                        $door_deal_earnings_per_performer_per_hour_sum += $review_earnings_per_performer_per_hour;
                    }
                    if ('Bar Deal' == get_field('_comp_structure_string')) {
                        $bar_deal_review_count++;
                        $bar_deal_earnings_sum += $review_earnings;
                        $bar_deal_earnings_per_performer_sum += $review_earnings_per_performer;
                        $bar_deal_earnings_per_hour_sum += $review_earnings_per_hour;
                        $bar_deal_earnings_per_performer_per_hour_sum += $review_earnings_per_performer_per_hour;
                    }
                }
            }

            // Calc averages
            $overall_rating_average = round(($review_count > 0) ? $overall_rating_sum/$review_count : 0, 2);
            $earnings_average = round(($review_count > 0) ? $earnings_sum/$review_count : 0, 2);
            $earnings_per_performer_average = round(($review_count > 0) ? $earnings_per_performer_sum/$review_count : 0, 2);
            $earnings_per_hour_average = round(($review_count > 0) ? $earnings_per_hour_sum/$review_count : 0, 2);
            $earnings_per_performer_per_hour_average = round(($review_count > 0) ? $earnings_per_performer_per_hour_sum/$review_count : 0, 2);
            $guarantee_earnings_average = round(($guarantee_review_count > 0) ? $guarantee_earnings_sum/$guarantee_review_count : 0, 2);
            $guarantee_earnings_per_performer_average = round(($guarantee_review_count > 0) ? $guarantee_earnings_per_performer_sum/$guarantee_review_count : 0, 2);
            $guarantee_earnings_per_hour_average = round(($guarantee_review_count > 0) ? $guarantee_earnings_per_hour_sum/$guarantee_review_count : 0, 2);
            $guarantee_earnings_per_performer_per_hour_average = round(($guarantee_review_count > 0) ? $guarantee_earnings_per_performer_per_hour_sum/$guarantee_review_count : 0, 2);
            $door_deal_earnings_average = round(($door_deal_review_count > 0) ? $door_deal_earnings_sum/$door_deal_review_count : 0, 2);
            $door_deal_earnings_per_performer_average = round(($door_deal_review_count > 0) ? $door_deal_earnings_per_performer_sum/$door_deal_review_count : 0, 2);
            $door_deal_earnings_per_hour_average = round(($door_deal_review_count > 0) ? $door_deal_earnings_per_hour_sum/$door_deal_review_count : 0, 2);
            $door_deal_earnings_per_performer_per_hour_average = round(($door_deal_review_count > 0) ? $door_deal_earnings_per_performer_per_hour_sum/$door_deal_review_count : 0, 2);
            $bar_deal_earnings_average = round(($bar_deal_review_count > 0) ? $bar_deal_earnings_sum/$bar_deal_review_count : 0, 2);
            $bar_deal_earnings_per_performer_average = round(($bar_deal_review_count > 0) ? $bar_deal_earnings_per_performer_sum/$bar_deal_review_count : 0, 2);
            $bar_deal_earnings_per_hour_average = round(($bar_deal_review_count > 0) ? $bar_deal_earnings_per_hour_sum/$bar_deal_review_count : 0, 2);
            $bar_deal_earnings_per_performer_per_hour_average = round(($bar_deal_review_count > 0) ? $bar_deal_earnings_per_performer_per_hour_sum/$bar_deal_review_count : 0, 2);

            // update venue meta data
            $update_args = array(
                'ID' => $venue_post_id,
                'meta_input' => array(
                    '_review_count' => $review_count,
                    '_guarantee_review_count' => $guarantee_review_count,
                    '_door_deal_review_count' => $door_deal_review_count,
                    '_bar_deal_review_count' => $bar_deal_review_count,
                    '_overall_rating' => $overall_rating_average,
                    '_average_earnings' => $earnings_average,
                    '_average_earnings_per_performer' => $earnings_per_performer_average,
                    '_average_earnings_per_hour' => $earnings_per_hour_average,
                    '_average_earnings_per_performer_per_hour' => $earnings_per_performer_per_hour_average,
                    '_guarantee_average_earnings' => $guarantee_earnings_average,
                    '_guarantee_average_earnings_per_performer' => $guarantee_earnings_per_performer_average,
                    '_guarantee_average_earnings_per_hour' => $guarantee_earnings_per_hour_average,
                    '_guarantee_average_earnings_per_performer_per_hour' => $guarantee_earnings_per_performer_per_hour_average,
                    '_door_deal_average_earnings' => $door_deal_earnings_average,
                    '_door_deal_average_earnings_per_performer' => $door_deal_earnings_per_performer_average,
                    '_door_deal_average_earnings_per_hour' => $door_deal_earnings_per_hour_average,
                    '_door_deal_average_earnings_per_performer_per_hour' => $door_deal_earnings_per_performer_per_hour_average,
                    '_bar_deal_average_earnings' => $bar_deal_earnings_average,
                    '_bar_deal_average_earnings_per_performer' => $bar_deal_earnings_per_performer_average,
                    '_bar_deal_average_earnings_per_hour' => $bar_deal_earnings_per_hour_average,
                    '_bar_deal_average_earnings_per_performer_per_hour' => $bar_deal_earnings_per_performer_per_hour_average,
                ),
            );
            $update_result = wp_update_post( wp_slash($update_args), true );
            if( is_wp_error( $update_result ) ) {
                return $venue_post_id->get_error_message();
            }
        }
    }
    return;
}

add_action('rest_api_init', function () {
  register_rest_route( 'venues/v1', 'stats', [
    'methods' => 'GET',
    'callback' => 'update_venue_stats',
  ]);
});
