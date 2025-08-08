<?php
function create_user_inquiry($args) {

    // Auto invite listings
    $max_listing_invites = $args['meta_input']['max_listing_invites'];
    $listings_invited    = $args['meta_input']['listings_invited'];
    if (count($listings_invited) < $max_listing_invites) {

        // Get suggestions
        $ensemble_size  = $args['meta_input']['ensemble_size'];
        $inquiry_genres = $args['tax_input']['genre'];
        $result = get_listings([
            'ensemble_size' => $ensemble_size,
            'genres'        => $inquiry_genres,
            'exclude'       => $listings_invited,
        ]);
        $suggestions = array_column($result['listings'], 'post_id');

        // update listings_invited
        $remaining_slots = $max_listing_invites - count($listings_invited);
        $new_invites = array_slice($suggestions, 0, $remaining_slots);
        $listings_invited = array_merge($listings_invited, $new_invites);
        $args['meta_input']['listings_invited'] = $listings_invited;
    }

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
    notify_listings_invited($user_id, $inquiry_id, $listings_invited, $args['meta_input']['subject']);

    // Get inquiry page link
    $inquiry_link = site_url('/messages?iid=' . $inquiry_id);

    // Notify inquiry creator about thier new inquiry
    $user_data = get_userdata($user_id);
    $owner_email = $user_data->user_email;
    $message = 'Thank you for creating an inquiry on HireMoreMusicians.com. You can see responses to your inquiry here: ' . $inquiry_link;
    wp_mail($owner_email, 'Your inquiry has been created!', $message);

    // Notify admin about new inquiry
    $message = 'New inquiry has been created by ' . $owner_email . '. Subject: ' . $args['meta_input']['subject'];
    wp_mail(ADMIN_NOTIFICATION_EMAIL, 'New Inquiry by ' . $owner_email, $message);

    return [
        'post_id'   => $inquiry_id,
        'subject'   => $args['meta_input']['subject'],
        'listings'  => $listings_invited,
        'permalink' => $inquiry_link,
    ];
}
