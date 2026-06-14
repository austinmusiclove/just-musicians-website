<?php

function create_compensation_report($args) {

    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', 'You must be logged in to contribute.');
    }

    $author_id          = get_current_user_id();
    $author_email       = $args['meta_input']['author_email'];
    $venue_name         = wp_unslash($args['meta_input']['venue_name']);
    $venue_post_id      = $args['meta_input']['venue_post_id'];
    $total_earnings     = $args['meta_input']['total_earnings'];
    $args['post_title'] = "{$venue_name} - venueID:{$venue_post_id} - {$author_email} - \${$total_earnings}";

    // Create post
    $post_id = wp_insert_post($args);
    if (is_wp_error($post_id) || !$post_id) {
        return new WP_Error('creation_failed', 'Failed to create report.');
    }

    // Send email notification to author
    send_author_comp_report_confirmation_email($author_email, $venue_name, $venue_post_id, $author_id);

    return [
        'post_id' => $post_id,
    ];
}

