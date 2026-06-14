<?php
function create_event($args) {

    // Create post
    $event_id = wp_insert_post($args);
    if (is_wp_error($event_id) || !$event_id) {
        return new WP_Error('creation_failed', 'Failed to create event.');
    }

    // Auto invite listings
    $auto_rfp            = $args['meta_input']['auto_rfp'] ?? false;
    $inquiry_listing     = $args['inquiry_listing'];
    $listings_to_invite   = $inquiry_listing ? [$inquiry_listing] : [];
    $max_listing_invites = DEFAULT_QUOTES_REQUESTED;
    if ($auto_rfp) {

        // Get suggestions
        $ensemble_size  = $args['tax_input']['ensemble_size'];
        $event_genres = $args['tax_input']['genre'];
        $result = get_listings([
            'ensemble_size' => $ensemble_size,
            'genres'        => $event_genres,
            'exclude'       => $listings_to_invite,
        ]);
        $suggestions = array_column($result['listings'], 'post_id');

        // update listings_to_invite
        $remaining_slots = $max_listing_invites - count($listings_to_invite);
        $new_invites = array_slice($suggestions, 0, $remaining_slots);
        $listings_to_invite = array_merge($listings_to_invite, $new_invites);
    }

    // Invite listings to respond
    foreach($listings_to_invite as $listing_id) {
        create_proposal($event_id, $listing_id, 'requested');
        notify_listing_proposal_request($event_id, $listing_id, $args['meta_input']['event_name']);
    }

    // Get event page link
    // TODO Create single event and make sure it has access control and get permalink here
    $event_link = get_permalink($event_id);

    $event_name = $args['meta_input']['event_name'];
    $user_id = get_current_user_id();
    send_creator_new_event_email($user_id, $event_name, $event_link);
    send_admin_new_event_email($user_id, $event_name);

    return [
        'post_id'    => $event_id,
        'event_name' => $args['meta_input']['event_name'],
        'listings'   => $listing_invites,
        'permalink'  => $event_link,
    ];
}
