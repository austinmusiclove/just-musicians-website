<?php

function get_proposals_by_events_listings($event_ids, $listing_ids) {
    if (empty($event_ids) || empty($listing_ids)) {
        return [];
    }

    $proposal_map = [];
    $query = new WP_Query([
        'post_type'   => 'proposal',
        'post_status' => 'publish',
        'author'      => get_current_user_id(),
        'nopaging'    => true,
        'meta_query'  => [
            'relation' => 'AND',
            ['key' => 'event',   'value' => $event_ids,   'compare' => 'IN'],
            ['key' => 'listing', 'value' => $listing_ids, 'compare' => 'IN'],
        ],
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        $lid = (int) get_post_meta(get_the_ID(), 'listing', true);
        $eid = (int) get_post_meta(get_the_ID(), 'event', true);
        $status = get_post_meta(get_the_ID(), 'status', true);

        $proposal_map[$lid][$eid] = [
            'availability' => $status == 'stale' ? '' : get_post_meta(get_the_ID(), 'availability', true),
        ];
    }
    wp_reset_postdata();

    return $proposal_map;
}
