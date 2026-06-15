<?php

function get_proposals_with_data($proposal_ids) {

    $proposals = [];
    foreach ($proposal_ids as $proposal_id) {
        $event_id   = (int) get_post_meta($proposal_id, 'event', true);
        $listing_id = (int) get_post_meta($proposal_id, 'listing', true);

        if (!$event_id) continue;
        if (!$listing_id) continue;

        $proposals[] = [
            'proposal_id'  => $proposal_id,
            'listing_id'   => $listing_id,
            'listing_name' => get_post_meta($listing_id, 'name', true);
            'status'       => get_post_meta($proposal_id, 'status', true) ?: 'requested',
            'event'        => [
                'event_id'     => $event_id,
                'event_name'   => get_field('event_name', $event_id),
                'start_date'   => get_field('start_date', $event_id),
                'end_date'     => get_field('end_date', $event_id),
                'start_time'   => get_field('start_time', $event_id),
                'end_time'     => get_field('end_time', $event_id),
                'city'         => get_field('city', $event_id),
                'state'        => get_field('state', $event_id),
                'details'      => get_field('details', $event_id),
                'budget'       => get_field('budget', $event_id),
                'compensation' => get_field('compensation', $event_id),
                'permalink'    => get_permalink($event_id),
            ],
        ];
    }

    return $proposals;
}
