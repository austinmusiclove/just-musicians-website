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
            $sales_deal_review_count = 0;
            $overall_rating_sum = 0;
            $earnings_sum = 0;
            $earnings_per_performer_sum = 0;
            $earnings_per_hour_sum = 0;
            $earnings_per_performer_per_hour_sum = 0;

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
                        'value' => $venue_post_id,
                        'compare' => '=='
                    )
                )
            );
            $venue_reviews_query = new WP_Query($args);
            if ($venue_reviews_query->have_posts()) {
                while( $venue_reviews_query->have_posts() ) {
                    $venue_reviews_query->the_post();
                    $review_count++;
                    if (get_post_meta(get_the_ID(), '_has_guarantee_comp' , true)) { $guarantee_review_count++; }
                    if (get_post_meta(get_the_ID(), '_has_door_comp' , true)) { $door_deal_review_count++; }
                    if (get_post_meta(get_the_ID(), '_has_sales_comp' , true)) { $sales_deal_review_count++; }
                    $overall_rating_sum += (int)get_post_meta(get_the_ID(), 'overall_rating' , true);
                    $earnings_sum += (float)get_post_meta(get_the_ID(), 'total_earnings' , true);
                    $earnings_per_performer_sum += (float)get_post_meta(get_the_ID(), '_earnings_per_performer' , true);
                    $earnings_per_hour_sum += (float)get_post_meta(get_the_ID(), '_earnings_per_hour' , true);
                    $earnings_per_performer_per_hour_sum += (float)get_post_meta(get_the_ID(), '_earnings_per_performer_per_hour' , true);
                }
            }

            // Calc averages
            $overall_rating_average = round(($review_count > 0) ? $overall_rating_sum/$review_count : 0, 2);
            $earnings_average = round(($review_count > 0) ? $earnings_sum/$review_count : 0, 2);
            $earnings_per_performer_average = round(($review_count > 0) ? $earnings_per_performer_sum/$review_count : 0, 2);
            $earnings_per_hour_average = round(($review_count > 0) ? $earnings_per_hour_sum/$review_count : 0, 2);
            $earnings_per_performer_per_hour_average = round(($review_count > 0) ? $earnings_per_performer_per_hour_sum/$review_count : 0, 2);

            // update venue meta data
            $update_args = array(
                'ID' => $venue_post_id,
                'meta_input' => array(
                    '_review_count' => $review_count,
                    '_guarantee_review_count' => $guarantee_review_count,
                    '_door_deal_review_count' => $door_deal_review_count,
                    '_sales_deal_review_count' => $sales_deal_review_count,
                    '_overall_rating' => $overall_rating_average,
                    '_average_earnings' => $earnings_average,
                    '_average_earnings_per_performer' => $earnings_per_performer_average,
                    '_average_earnings_per_hour' => $earnings_per_hour_average,
                    '_average_earnings_per_performer_per_hour' => $earnings_per_performer_per_hour_average,
                ),
            );
            $update_result = wp_update_post( wp_slash($update_args), true );
            if( is_wp_error( $update_result ) ) {
                return $venue_post_id->get_error_message();
            }
            //} // for testing
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
