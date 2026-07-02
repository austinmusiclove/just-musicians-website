<?php

function create_application($args) {

    $auth = user_can_create_application();
    if (is_wp_error($auth)) {
        return $auth;
    }

    $application_id = wp_insert_post($args, true);
    if (is_wp_error($application_id) || !$application_id) {
        return new WP_Error('creation_failed', 'Failed to create application.');
    }

    send_creator_new_application_email(get_current_user_id(), $application_id);

    return [
        'post_id'   => $application_id,
        'permalink' => get_permalink($application_id),
    ];
}
