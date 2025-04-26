<?php

function get_collections($args) {

    // Handle paging
    $nothumbnails = (!empty($args['nothumbnails'])) ? rest_sanitize_boolean($args['nothumbnails']) : false;
    $nopaging = (!empty($args['nopaging'])) ? rest_sanitize_boolean($args['nopaging']) : false;
    $sanitized_page = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page = $page + 1;
    $max_num_results = 1;
    $max_num_pages = 1;

    // Set up
    $collections = [];
    $current_user_id = get_current_user_id();

    // Get Favorites on page 1 only
    if ($page == 1) {
        $favorites = get_user_meta($current_user_id, 'favorites', true);
        $favorites = is_array($favorites) ? array_map(fn($post_id) => strval($post_id), $favorites) : [];

        // Get favorites thumbnail(s)
        $thumbnails = $nothumbnails ? [] : get_thumbnails_from_listings($favorites);

        $collections[] = [
            'post_id'        => 'favorites',
            'name'           => 'Favorites',
            'listings'       => $favorites,
            'thumbnail_urls' => $thumbnails,
            'permalink'      => '/collection/favorites',
        ];
    }

    // Get Collections
    $user_collections = get_user_meta($current_user_id, 'collections', true);
    if ( $user_collections and count($user_collections) > 0 ) {

        $query_args = [
            'post_type'      => 'collection',
            'post__in'       => $user_collections,
            'post_status'    => 'publish',
            'orderby'        => 'post__in',
        ];
        if ($nopaging) {
            $query_args['nopaging'] = true;
        } else {
            $query_args['paged']          = $page;
            $query_args['posts_per_page'] = 10;
        }
        $query = new WP_Query($query_args);
        $max_num_results = $query->found_posts + 1;
        $max_num_pages = $query->max_num_pages;

        while ($query->have_posts()) {
            $query->the_post();

            // Get thumbnail(s)
            $listings = get_field('listings') ?? [];
            $listings = is_array($listings) ? array_map(fn($post_id) => strval($post_id), $listings) : [];
            $thumbnails = $nothumbnails ? [] : get_thumbnails_from_listings($listings);

            $collections[] = [
                'post_id'        => get_the_ID(),
                'name'           => get_field('name'),
                'listings'       => $listings,
                'thumbnail_urls' => $thumbnails,
                'permalink'      => get_the_permalink(),
            ];
        }
    }

    return [
        'collections'     => $collections,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}

function get_thumbnails_from_listings($listing_post_ids) {
    if (count($listing_post_ids) >= 4) {
        return [
            get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing'),
            get_the_post_thumbnail_url($listing_post_ids[1], 'standard-listing'),
            get_the_post_thumbnail_url($listing_post_ids[2], 'standard-listing'),
            get_the_post_thumbnail_url($listing_post_ids[3], 'standard-listing'),
        ];
    } else if (count($listing_post_ids) >= 1) {
        return [ get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing') ];
    }
    return [];
}
