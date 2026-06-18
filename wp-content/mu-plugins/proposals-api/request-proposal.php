<?php

function request_proposal($event_id, $listing_id) {

    // Validate event_id and listing_id
    if (!is_numeric($event_id) || !is_numeric($listing_id)) {
        return new WP_Error(400, 'Invalid event or listing ID :: ' . $event_id . ' :: ' . $listing_id);
    }

    // Get Event
    $event = get_post($event_id);
    if (!$event || $event->post_type !== 'event') {
        return new WP_Error(404, 'Event not found');
    }

    // Get listings
    $listings = hm_get_listing_ids_by_event_id($event_id);

    // Create proposal if one is not already created
    if (!in_array($listing_id, $listings)) {
        $listings[] = $listing_id;

        $result = create_inquiry_proposal([
            'event'   => $event_id,
            'listing' => $listing_id,
        ]);

        if (is_wp_error($result)) {
            return $result;
        }

        $proposal_id = $result;
        $event_name  = get_post_meta($event_id, 'event_name', true);
        notify_listing_proposal_request($proposal_id, $event_id, $listing_id, $event_name);
    }

    return new WP_REST_Response(['success' => true, 'listings' => $listings], 200);
}

