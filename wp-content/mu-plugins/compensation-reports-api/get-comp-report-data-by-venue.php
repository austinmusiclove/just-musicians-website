<?php
function get_comp_report_data_by_venue() {
    $all_comp_reports = new WP_Query([
        'post_type' => 'comp_report',
        'post_status' => 'publish',
        'nopaging' => true,
    ]);

    $venue_data = [];

    if ($all_comp_reports->have_posts()) {
        while ($all_comp_reports->have_posts()) {
            $all_comp_reports->the_post();
            $venue_post_id = get_field('venue_post_id');

            if (!$venue_post_id) continue;

            if (!isset($venue_data[$venue_post_id])) {
                $venue_data[$venue_post_id] = [
                    'earnings' => [],
                    'ensemble_sizes' => [],
                    'set_lengths' => [],
                    'payment_methods' => [],
                    'payment_speeds' => [],
                ];
            }

            $venue_data[$venue_post_id]['earnings'][] = (float) get_field('total_earnings');
            $venue_data[$venue_post_id]['ensemble_sizes'][] = (float) get_field('total_performers');
            $venue_data[$venue_post_id]['set_lengths'][] = (float) get_field('minutes_performed');

            $payment_method = get_field('payment_method');
            $payment_speed = get_field('payment_speed');

            if ($payment_method) {
                $venue_data[$venue_post_id]['payment_methods'][] = $payment_method;
            }
            if ($payment_speed) {
                $venue_data[$venue_post_id]['payment_speeds'][] = $payment_speed;
            }
        }
    }
    wp_reset_postdata();

    $processed_venues = [];
    foreach ($venue_data as $venue_id => $data) {
        $venue_post = get_post($venue_id);
        if (!$venue_post || $venue_post->post_status !== 'publish') continue;

        $processed_venues[] = [
            'ID' => $venue_post->ID,
            'name' => $venue_post->post_title,
            'link' => esc_url(get_permalink($venue_id)),
            'total_reports' => count($data['earnings']),
            'median_earnings' => calculate_median($data['earnings']),
            'average_earnings' => calculate_average($data['earnings']),
            'avg_ensemble_size' => calculate_average($data['ensemble_sizes']),
            'avg_set_length' => calculate_average($data['set_lengths']),
            'payment_method' => get_mode($data['payment_methods']),
            'payment_speed' => get_mode($data['payment_speeds']),
        ];
    }

    return $processed_venues;
}
