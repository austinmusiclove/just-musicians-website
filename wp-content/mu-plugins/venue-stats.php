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
            $overall_rating_sum = 0;
            $earnings_phpp_sum = 0;
            $venues_query->the_post();
            $venue_post_id = get_the_ID();

            //if ($venue_post_id == 128) { // for testing; 128 is half step

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
                    $overall_rating_sum += (int)get_post_meta(get_the_ID(), 'overall_rating' , true);
                    $earnings_phpp = (float)get_post_meta(get_the_ID(), '_earnings_per_hour_per_performer' , true);
                    $earnings_phpp_sum += $earnings_phpp;
                    $review_count++;
                }
            }
            $overall_rating_average = round(($review_count > 0) ? $overall_rating_sum/$review_count : 0, 2);
            $earnings_phpp_average = round(($review_count > 0) ? $earnings_phpp_sum/$review_count : 0, 2);

            // update venue meta data
            $update_args = array(
                'ID' => $venue_post_id,
                'meta_input' => array(
                    '_review_count' => $review_count,
                    '_overall_rating' => $overall_rating_average,
                    '_average_pay' => $earnings_phpp_average,
                ),
            );
            $update_result = wp_update_post( wp_slash($update_args), true );
            if( is_wp_error( $update_result ) ) {
                return $venue_post_id->get_error_message();
            }
            //} // for testing
        }
    }
}
?>
