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
    ];
    if ($nopaging) {
        $query_args['nopaging'] = true;
    } else {
        $query_args['paged']          = $page;
        $query_args['posts_per_page'] = 10;
    }

    $meta_conditions = [];

    if (!empty($args['start_date_after'])) {
        $meta_conditions[] = [
            'key'     => 'start_date',
            'value'   => sanitize_text_field($args['start_date_after']),
            'compare' => '>=',
            'type'    => 'DATE',
        ];
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'ASC';
    }

    if (!empty($args['start_date_before'])) {
        $meta_conditions[] = [
            'key'     => 'start_date',
            'value'   => sanitize_text_field($args['start_date_before']),
            'compare' => '<',
            'type'    => 'DATE',
        ];
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'DESC';
    }

    if (!empty($meta_conditions)) {
        $query_args['meta_query'] = $meta_conditions;
    }

    $query = new WP_Query($query_args);
    $max_num_results = $query->found_posts;
    $max_num_pages = $query->max_num_pages;

    while ($query->have_posts()) {
        $query->the_post();

        $event_id = get_the_ID();
        $listings = hm_get_listing_ids_by_event_id($event_id);

        $events[] = [
            'post_id'        => $event_id,
            'event_name'     => get_field('event_name'),
            'start_date'     => get_field('start_date'),
            'end_date'       => get_field('end_date', $event_id),
            'start_time'     => get_field('start_time', $event_id),
            'end_time'       => get_field('end_time', $event_id),
            'address_line_1' => get_field('address_line_1', $event_id),
            'address_line_2' => get_field('address_line_2', $event_id),
            'city'           => get_field('city', $event_id),
            'state'          => get_field('state', $event_id),
            'zip_code'       => get_field('zip_code', $event_id),
            'listings'       => $listings,
            'permalink'      => get_permalink($event_id),
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

