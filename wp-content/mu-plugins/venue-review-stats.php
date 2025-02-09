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
 * Description:       Calculates venue review stats from available data in the venue reviews
 * Version:           1.0.0
 * Author:            Just Musicians
 * Author URI:        justmusicians.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       venue-stats
 * Domain Path:       /languages
 */

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
                return $venue_post_id->get_error_message();
            }
        }
    }
    return;
}

add_action('rest_api_init', function () {
  register_rest_route( 'v1', 'venue_review/stats', [
    'methods' => 'GET',
    'callback' => 'update_venue_review_stats',
  ]);
});
