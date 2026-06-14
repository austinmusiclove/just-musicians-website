<?php
function create_proposal($event_id, $listing_id, $status = 'requested') {
    return wp_insert_post([
        'post_type'  => 'proposal',
        'meta_input' => [
            'listing' => $listing_id,
            'event'   => $event_id,
            'status'  => $status,
        ],
    ]);
}
