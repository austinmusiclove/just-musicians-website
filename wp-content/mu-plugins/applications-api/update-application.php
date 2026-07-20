<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function update_user_application($args) {

    $auth = user_can_update_application($args['ID']);
    if (is_wp_error($auth)) {
        return $auth;
    }

    return update_application($args);
}

function update_application($args) {

    $post_id = isset($args['ID']) ? (int) $args['ID'] : 0;
    if (!$post_id) {
        return new WP_Error('invalid_id', 'Invalid application ID');
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'application') {
        return new WP_Error('invalid_post_type', 'Invalid application');
    }

    $application_id = wp_update_post($args, true);
    if (!$application_id) {
        return new WP_Error('update_failed', 'Failed to update application');
    }

    return [
        'post_id'     => $post_id,
        'title'       => get_post_meta($post_id, 'title', true),
        'description' => get_post_meta($post_id, 'description', true),
    ];
}
