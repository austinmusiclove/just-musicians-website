<?php

function create_application($args) {

    $application_id = wp_insert_post($args, true);
    if (is_wp_error($application_id) || !$application_id) {
        return new WP_Error('creation_failed', 'Failed to create application.');
    }

    return [
        'post_id'   => $application_id,
        'permalink' => get_permalink($application_id),
    ];
}
