<?php
function create_proposal($event_id, $listing_id, $status = 'requested') {
    $event_name = get_post_meta($event_id, 'event_name', true);
    $listing_name = get_post_meta($listing_id, 'name', true);
    return wp_insert_post([
        'post_type'   => 'proposal',
        'post_status' => 'publish',
        'post_title'  => $event_name . ' :: ' . $listing_name,
        'meta_input' => [
            'listing' => $listing_id,
            'event'   => $event_id,
            'status'  => $status,
        ],
    ]);
}
