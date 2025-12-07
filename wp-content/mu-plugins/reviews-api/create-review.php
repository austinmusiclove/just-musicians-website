<?php

function create_review($args) {

    // Create post
    $review_id = wp_insert_post($args);
    if (is_wp_error($review_id) || !$review_id) {
        return new WP_Error('creation_failed', 'Failed to create review.');
    }

    return [
        'post_id'   => $review_id,
    ];
}
