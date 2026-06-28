<?php

function get_user_application_submissions($args = []) {

    $user_id = get_current_user_id();
    if (!$user_id) {
        return [
            'submissions'   => [],
            'max_num_pages' => 0,
            'next_page'     => 1,
        ];
    }

    // Get user listing ids
    $user_listing_ids = get_user_meta($user_id, 'listings', true);
    $listing_ids = $user_listing_ids;

    // If listing ids are passed in, accept only the ones that are also in user listings
    if (!empty($args['listing_ids'])) {
        $listing_ids = array_intersect(
            (array) $user_listing_ids,
            array_map('intval', (array) $args['listing_ids'])
        );
    }

    if (empty($listing_ids)) {
        return [
            'submissions'   => [],
            'max_num_pages' => 0,
            'next_page'     => 1,
        ];
    }

    $sanitized_page = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page           = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page      = $page + 1;

    $meta_query = [
        [
            'key'     => 'listing',
            'value'   => $listing_ids,
            'compare' => 'IN',
        ],
    ];

    if (!empty($args['status']) && $args['status'] !== 'all') {
        $meta_query[] = [
            'key'   => 'status',
            'value' => sanitize_text_field($args['status']),
        ];
    }

    $query = new WP_Query([
        'post_type'      => 'app_submission',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'paged'          => $page,
        'meta_query'     => $meta_query,
    ]);

    $submissions = [];
    while ($query->have_posts()) {
        $query->the_post();

        $application_id = get_post_meta(get_the_ID(), 'application', true);
        $listing_id     = get_post_meta(get_the_ID(), 'listing', true);

        $submissions[] = [
            'post_id'              => get_the_ID(),
            'application_id'       => $application_id,
            'application_title'    => $application_id ? get_post_meta($application_id, 'title', true) : '',
            'listing_id'           => $listing_id,
            'listing_name'         => $listing_id ? get_post_meta($listing_id, 'name', true) : '',
            'listing_thumbnail_url'=> $listing_id ? get_the_post_thumbnail_url($listing_id, 'thumbnail') : '',
            'status'               => get_post_meta(get_the_ID(), 'status', true),
            'message'              => get_post_meta(get_the_ID(), 'message', true),
            'updated'              => get_the_modified_time('F j, Y'),
        ];
    }

    wp_reset_postdata();

    return [
        'submissions'   => $submissions,
        'max_num_pages' => $query->max_num_pages,
        'next_page'     => $next_page,
    ];
}
