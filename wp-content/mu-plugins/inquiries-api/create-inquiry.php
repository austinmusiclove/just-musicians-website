<?php
function create_user_inquiry($args) {

    // Create post
    $inquiry_id = wp_insert_post($args);
    if (is_wp_error($inquiry_id) || !$inquiry_id) {
        return new WP_Error('creation_failed', 'Failed to create inquiry.');
    }

    // Get user's existing collection IDs
    $user_id = get_current_user_id();
    $user_inquiries = get_user_meta($user_id, 'inquiries', true);
    if (!is_array($user_inquiries)) { $user_inquiries = []; }

    // Prepend inquiry ID to user's inquiries meta (keeps them in order of latest first)
    array_unshift($user_inquiries, $inquiry_id);
    $user_inquiries = array_unique($user_inquiries);

    // Save inquiries to user meta
    update_user_meta($user_id, 'inquiries', $user_inquiries);

    // Send messages to invited listings
    notify_listings_invited($user_id, $inquiry_id, $args['meta_input']['listings_invited'], $args['meta_input']['details']);

    // Get permalink
    $permalink = get_permalink($inquiry_id);

    return [
        'post_id'   => $inquiry_id,
        'subject'   => $args['meta_input']['subject'],
        'listings'  => $args['meta_input']['listings_invited'] ? $args['meta_input']['listings_invited'] : [],
        'permalink' => site_url('/messages/?iid=' . $inquiry_id),
    ];
}
