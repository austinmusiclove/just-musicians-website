<?php
function update_user_inquiry($args) {

    // Must be logged in
    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', 'You must be logged in to update user inquiry');
    }

    // Must provide valid post id
    $user_id = get_current_user_id();
    $post_id = isset($args['ID']) ? (int) $args['ID'] : 0;
    if (!$post_id) {
        return new WP_Error('invalid_id', 'Invalid inquiry ID');
    }

    // Allow admins
    if (!current_user_can('manage_options')) {

        // Get user's inquiries (stored as array of post IDs)
        $user_inquiries = get_user_meta($user_id, 'inquiries', true);
        if (!is_array($user_inquiries)) { $user_inquiries = []; }

        if (!in_array($post_id, $user_inquiries)) {
            return new WP_Error('forbidden', 'You are not allowed to edit this inquiry.');
        }
    }

    // Update post
    $inquiry_id = wp_update_post($args, true);
    if (is_wp_error($inquiry_id) || !$inquiry_id) {
        return new WP_Error('update_failed', 'Failed to update inquiry.');
    }

    return [
        'post_id'   => $inquiry_id,
        'subject'   => $args['meta_input']['subject'],
        'details'   => $args['meta_input']['details'],
    ];
}
