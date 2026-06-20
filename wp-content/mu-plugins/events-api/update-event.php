<?php
function update_user_event($args) {

    // Authenticate
    if (!current_user_can('manage_options')) {
        if (!user_owns_event()) {
            return new WP_Error('access_denied', 'You must be the author of the event to update it');
        }
    }

    return update_event($args);
}

function update_event($args) {

    // Must provide valid post id
    $post_id = isset($args['ID']) ? (int) $args['ID'] : 0;
    if (!$post_id) {
        return new WP_Error('invalid_id', 'Invalid event ID');
    }

    // Update post
    $event_id = wp_update_post($args, true);
    if (is_wp_error($event_id) || !$event_id) {
        return new WP_Error('update_failed', 'Failed to update event');
    }

    // Returns all meta key value pairs
    $meta = array_map(fn($val) => $val[0], get_post_meta($event_id, '', false));

    return [
        'post_id'   => $event_id,
        'post_meta' => $meta,
    ];
}
