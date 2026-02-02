<?php

function create_compensation_report($args) {

    $author_id = get_current_user_id();
    $args['post_title'] = "{$args['meta_input']['venue_name']} - venueID:{$args['meta_input']['venue']} - {$args['meta_input']['author_email']} - \${$args['meta_input']['total_earnings']}";

    // Create post
    $post_id = wp_insert_post($args);
    if (is_wp_error($post_id) || !$post_id) {
        return new WP_Error('creation_failed', 'Failed to create report.');
    }

    return [
        'post_id' => $post_id,
    ];
}
