<?php

function update_venue_stats($venue_id) {
    error_log('update venue stats: ' . $venue_id);
    $comp_report_count = 0;
    $average_earnings_per_gig = 0;
    $average_earnings_per_hour = 0;
    $average_earnings_per_performer = 0;
    $average_earnings_per_performer_per_hour = 0;
    $average_ensemble_size = 0;
    $payment_speeds = [];
    $payment_methods = [];
    $genres_hired = [];

    // Get comp_reports for this venue
    $query_args = array(
        'post_type' => 'comp_report',
        'nopaging' => true,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'venue_post_id',
                'value' => $venue_id,
                'compare' => '='
            )
        )
    );
    $comp_report_query = new WP_Query($query_args);
    if ($comp_report_query->have_posts()) {

        while( $comp_report_query->have_posts() ) {
            $comp_report_query->the_post();
            $total_performers  = (float)get_field('total_performers');
            $minutes_performed = (float)get_field('minutes_performed');
            $hours_performed   = round($minutes_performed / 60, 2);
            $total_earnings    = (float)get_field('total_earnings');
            $payment_speed     = get_field('payment_speed');
            $payment_method    = get_field('payment_method');
            if (!get_field('exclude_from_stats') and $total_performers > 0 and $minutes_performed > 0) {
                $comp_report_count++;
                $average_ensemble_size                   += $total_performers;
                $average_earnings_per_gig                += $total_earnings;
                $average_earnings_per_hour               += $total_earnings / $hours_performed;
                $average_earnings_per_performer          += $total_earnings / $total_performers;
                $average_earnings_per_performer_per_hour += $total_earnings / $hours_performed / $total_performers;
                if (!empty($payment_speed)  and isset($payment_speeds[$payment_speed]))    { $payment_speeds[$payment_speed]   += 1; }
                if (!empty($payment_speed)  and !isset($payment_speeds[$payment_speed]))   { $payment_speeds[$payment_speed]    = 1; }
                if (!empty($payment_method) and isset($payment_methods[$payment_method]))  { $payment_methods[$payment_method] += 1; }
                if (!empty($payment_method) and !isset($payment_methods[$payment_method])) { $payment_methods[$payment_method]  = 1; }
            }
        }
    }

    // Calc averages
    if ($comp_report_count > 0) {
        $average_earnings_per_gig                = round($average_earnings_per_gig                / $comp_report_count, 2);
        $average_earnings_per_hour               = round($average_earnings_per_hour               / $comp_report_count, 2);
        $average_earnings_per_performer          = round($average_earnings_per_performer          / $comp_report_count, 2);
        $average_earnings_per_performer_per_hour = round($average_earnings_per_performer_per_hour / $comp_report_count, 2);
        $average_ensemble_size                   = round($average_ensemble_size                   / $comp_report_count, 2);
    }

    // update venue meta data
    $update_args = [
        'ID'         => $venue_id,
        'meta_input' => [
            '_comp_report_count'                       => $comp_report_count,
            '_average_earnings'                        => $average_earnings_per_gig,
            '_average_earnings_per_hour'               => $average_earnings_per_hour,
            '_average_earnings_per_performer'          => $average_earnings_per_performer,
            '_average_earnings_per_performer_per_hour' => $average_earnings_per_performer_per_hour,
            '_average_ensemble_size'                   => $average_ensemble_size,
            '_payment_speed'                           => json_encode($payment_speeds),
            '_payment_method'                          => json_encode($payment_methods),
            '_genres_hired'                            => $genres_hired,
        ],
    ];
    $update_result = wp_update_post( wp_slash($update_args), true );
    if( is_wp_error( $update_result ) ) {
        wp_reset_postdata();
        return $update_result->get_error_message();
    }
}
