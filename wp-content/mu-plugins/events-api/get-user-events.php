<?php

function get_user_events($args) {

    // Handle paging
    $nopaging        = (!empty($args['nopaging'])) ? rest_sanitize_boolean($args['nopaging']) : false;
    $sanitized_page  = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page            = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page       = $page + 1;
    $max_num_results = 0;
    $max_num_pages   = 0;


    // Get events
    $events = [];
    $query_args = [
        'post_type'   => 'event',
        'post_status' => 'publish',
        'author'      => get_current_user_id(),
        'orderby'     => 'start_date',
        'order'       => 'DESC',
    ];
    if ($nopaging) {
        $query_args['nopaging'] = true;
    } else {
        $query_args['paged']          = $page;
        $query_args['posts_per_page'] = 6;
    }

    if (!empty($args['start_date'])) {
        $query_args['meta_query'] = [
            [
                'key'     => 'start_date',
                'value'   => sanitize_text_field($args['start_date']),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
        ];
    }

    $query = new WP_Query($query_args);
    $max_num_results = $query->found_posts;
    $max_num_pages = $query->max_num_pages;

    while ($query->have_posts()) {
        $query->the_post();
        $event_id = get_the_ID();

        $listings = hm_get_listing_ids_by_event_id($event_id);

        $events[] = [
            'post_id'        => get_the_ID(),
            'event_name'     => get_field('event_name'),
            'details'        => get_field('details'),
            'listings'       => $listings,
            'permalink'      => get_permalink(get_the_ID()),
        ];
    }

    wp_reset_postdata();

    return [
        'events'          => $events,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}

