<?php
function update_user_event($args) {

    // Authenticate
    if (!current_user_can('manage_options')) {
        if (!user_owns_event(['event_id' => $args['ID']])) {
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

    $post_meta = array_map(fn($val) => $val[0], get_post_meta($event_id, '', false)); // Returns all meta key value pairs

    // Get times and dates with get_field to assure they come back in the format specified in ACF
    $post_meta['start_date'] = get_field('start_date', $event_id);
    $post_meta['end_date']   = get_field('end_date', $event_id);
    $post_meta['start_time'] = get_field('start_time', $event_id);
    $post_meta['end_time']   = get_field('end_time', $event_id);

    return array_merge([
        'post_id'       => $event_id,
        'genres'        => wp_get_post_terms($event_id, 'genre', ['fields' => 'names']),
        'ensemble_size' => wp_get_post_terms($event_id, 'ensemble_size', ['fields' => 'names']),
    ], $post_meta);
}
