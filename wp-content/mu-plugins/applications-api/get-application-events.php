<?php

function get_application_events($application_id) {
    if (!get_post($application_id) || get_post_type($application_id) !== 'application') {
        return [];
    }

    $event_ids = get_field('events', $application_id) ?: [];

    if (empty($event_ids)) {
        return [];
    }

    $query = new WP_Query([
        'post_type'   => 'event',
        'post_status' => 'publish',
        'post__in'    => $event_ids,
        'orderby'     => 'post__in',
    ]);

    $events = [];
    while ($query->have_posts()) {
        $query->the_post();
        $events[] = [
            'event_id'      => get_the_ID(),
            'event_name'    => get_field('event_name'),
            'start_date'    => get_field('start_date'),
            'request_quote' => get_field('request_quote'),
            'request_draw'  => get_field('request_draw'),
        ];
    }
    wp_reset_postdata();

    return $events;
}
