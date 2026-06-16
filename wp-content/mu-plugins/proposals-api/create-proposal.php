<?php
function create_proposal($args) {
    $event_id   = (int) ($args['event'] ?? 0);
    $listing_id = (int) ($args['listing'] ?? 0);

    $existing = new WP_Query([
        'post_type'      => 'proposal',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => [
            ['key' => 'event',   'value' => $event_id],
            ['key' => 'listing', 'value' => $listing_id],
        ],
    ]);

    if ($existing->have_posts()) {
        return $existing->posts[0];
    }

    $event_name   = get_post_meta($event_id, 'event_name', true);
    $listing_name = get_post_meta($listing_id, 'name', true);

    return wp_insert_post([
        'post_type'   => 'proposal',
        'post_status' => 'publish',
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
