<?php

function get_proposals_with_data($proposal_ids) {

    $proposals = [];
    foreach ($proposal_ids as $proposal_id) {
        $event_id = (int) get_post_meta($proposal_id, 'event', true);
        if (!$event_id) continue;

        $proposals[] = [
            'proposal_id' => $proposal_id,
            'listing_id'  => (int) get_post_meta($proposal_id, 'listing', true),
            'status'      => get_post_meta($proposal_id, 'status', true) ?: 'requested',
            'event'       => [
                'event_id'   => $event_id,
                'event_name' => get_field('event_name', $event_id),
                'permalink'  => get_permalink($event_id),
            ],
        ];
    }

    return $proposals;
}
