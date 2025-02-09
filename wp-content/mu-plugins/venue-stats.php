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

add_action('rest_api_init', function () {
  register_rest_route( 'venues/v1', 'stats', [
    'methods' => 'GET',
    'callback' => 'update_venue_stats',
  ]);
});
