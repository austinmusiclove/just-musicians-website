<?php

function get_user_inquiries($args) {

    // Handle paging
    $nothumbnails    = (!empty($args['nothumbnails'])) ? rest_sanitize_boolean($args['nothumbnails']) : false;
    $nopaging        = (!empty($args['nopaging'])) ? rest_sanitize_boolean($args['nopaging']) : false;
    $sanitized_page  = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page            = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page       = $page + 1;
    $max_num_results = 0;
    $max_num_pages   = 0;

    // Set up
    $inquiries = [];
    $current_user_id = get_current_user_id();

    // Get inquiries
    $user_inquiries = get_user_meta($current_user_id, 'inquiries', true);
    $user_inquiries = is_array($user_inquiries) ? array_map('strval', $user_inquiries) : [];
    if ( $user_inquiries and count($user_inquiries) > 0 ) {

        $query_args = [
            'post_type'      => 'inquiry',
            'post__in'       => $user_inquiries,
            'post_status'    => 'publish',
            'orderby'        => 'post__in',
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

            // Get thumbnail(s)
            $listing_ids = get_field('listings_invited') ?? [];
            $listing_ids = is_array($listing_ids) ? array_map(fn($post_id) => strval($post_id), $listing_ids) : [];
            $thumbnails = $nothumbnails ? [] : get_thumbnails_from_listings($listing_ids);

            // Filter out listings are not published
            $the_post = $GLOBALS['post'];
            $listings_by_id_result = get_listings_by_id([
                'listing_ids' => $listing_ids,
                'nopaging'    => true,
            ]);
            $GLOBALS['post'] = $the_post;

            $listings = $listings_by_id_result['listings'];
            $listing_ids = array_filter(array_column($listings, 'post_id'));
            $listing_ids = is_array($listing_ids) ? array_map(fn($post_id) => strval($post_id), $listing_ids) : [];

            $inquiries[] = [
                'post_id'        => get_the_ID(),
                'subject'        => get_field('subject'),
                'listings'       => $listing_ids,
                'thumbnail_urls' => $thumbnails,
                'permalink'      => get_the_permalink(),
            ];
        }
    }

    wp_reset_postdata();

    return [
        'inquiries'       => $inquiries,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}

