<?php

function create_proposal($args, $status = 'publish') {
    $event_id   = (int) ($args['event'] ?? 0);
    $listing_id = (int) ($args['listing'] ?? 0);

    $existing = hm_proposal_exists($listing_id, $event_id);
    if ($existing) {
        return $existing;
    }

    $event_name   = get_post_meta($event_id, 'event_name', true);
    $listing_name = get_post_meta($listing_id, 'name', true);

    return wp_insert_post([
        'post_type'   => 'proposal',
        'post_status' => $status,
        'post_title'  => $event_name . ' :: ' . $listing_name,
        'meta_input'  => [
            'event'        => $event_id,
            'listing'      => $listing_id,
            'status'       => $args['status'] ?? '',
            'availability' => $args['availability'] ?? '',
            'quote'        => $args['quote'] ?? '',
            'draw'         => $args['draw'] ?? false,
            'details'      => $args['details'] ?? '',
        ],
    ]);
}

function create_inquiry_proposal($args) {
    $event_id = (int) ($args['event'] ?? 0);

    $authorized = user_can_create_inquiry_proposal($event_id);
    if (is_wp_error($authorized)) {
        return $authorized;
    }

    $args['status'] = 'inquiry';
    return create_proposal($args);
}
