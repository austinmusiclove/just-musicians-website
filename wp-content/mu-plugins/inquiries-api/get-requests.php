<?php

function get_user_requests($args) {

    // Handle paging
    $nopaging        = (!empty($args['nopaging'])) ? rest_sanitize_boolean($args['nopaging']) : false;
    $sanitized_page  = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page            = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page       = $page + 1;
    $max_num_results = 0;
    $max_num_pages   = 0;

    // Set up
    $requests = [];
    $current_user_id = get_current_user_id();
    $user_listings   = get_user_meta($current_user_id, 'listings', true);
    $user_listings   = is_array($user_listings) ? array_map('strval', $user_listings) : [];

    if (count($user_listings) > 0) {

        // Get requests
        $meta_query = ['relation' => 'OR'];
        foreach ($user_listings as $listing_id) {
            $meta_query[] = [
                'key'     => 'listings_invited',
                'value'   => $listing_id,
                'compare' => 'LIKE',
            ];
        }
        $query_args = [
            'post_type'   => 'inquiry',
            'post_status' => 'publish',
            'meta_query'  => $meta_query,
        ];
        if ($nopaging) {
            $query_args['nopaging'] = true;
        } else {
            $query_args['paged']          = $page;
            $query_args['posts_per_page'] = 6;
        }

        $query = new WP_Query($query_args);
        $max_num_results = $query->found_posts;
        $max_num_pages = $query->max_num_pages;

        while ($query->have_posts()) {
            $query->the_post();

            $requests[] = [
                'post_id'               => get_the_ID(),
                'subject'               => get_field('subject'),
                'date_type'             => get_field('date_type'),
                'date'                  => get_field('date'),
                'date_time_details'     => get_field('date_time_details'),
                'time'                  => get_field('time'),
                'zip_code'              => get_field('zip_code'),
                'location_details'      => get_field('location_details'),
                'duration'              => get_field('duration'),
                'ensemble_size'         => get_field('ensemble_size'),
                'equipment_requirement' => get_field('equipment_requirement'),
                'equipment_details'     => get_field('equipment_details'),
                'details'               => get_field('details'),
                'genres'                => wp_get_post_terms(get_the_ID(), 'genre', ['fields' => 'names']),
            ];
        }

        wp_reset_postdata();
    }

    return [
        'requests'        => $requests,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}
