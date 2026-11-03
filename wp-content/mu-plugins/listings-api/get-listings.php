<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_listings($args) {

    $results = [];
    $lat                    = (!empty($args['lat']))                ? (float) $args['lat']                                             : 30.2672;  // Default to Austin, TX coordinates
    $lng                    = (!empty($args['lng']))                ? (float) $args['lng']                                             : -97.7431; // Default to Austin, TX coordinates
    $distance               = (!empty($args['distance']))           ? (float) $args['distance']                                        : 40;
    $search_term            = (!empty($args['search']))             ? sanitize_text_field($args['search'])                             : null;
    $name_search_term       = (!empty($args['name_search']))        ? sanitize_text_field($args['name_search'])                        : null;
    $verified               = (!empty($args['verified']))           ? rest_sanitize_boolean($args['verified'])                         : null;
    $sanitized_page         = (!empty($args['page']))               ? sanitize_text_field($args['page'])                               : null;
    $valid_categories       = (!empty($args['categories']))         ? validate_tax_input($args['categories'], 'mcategory')             : [];
    $valid_genres           = (!empty($args['genres']))             ? validate_tax_input($args['genres'], 'genre')                     : [];
    $valid_subgenres        = (!empty($args['subgenres']))          ? validate_tax_input($args['subgenres'], 'subgenre')               : [];
    $valid_instrumentations = (!empty($args['instrumentations']))   ? validate_tax_input($args['instrumentations'], 'instrumentation') : [];
    $valid_settings         = (!empty($args['settings']))           ? validate_tax_input($args['settings'], 'setting')                 : [];
    $valid_ensemble_sizes   = (!empty($args['ensemble_size']))      ? validate_tax_input($args['ensemble_size'], 'ensemble_size')      : [];
    $with_reviews           = (!empty($args['get_reviews']))        ? rest_sanitize_boolean($args['get_reviews'])                      : false;
    $postal_codes           = (!empty($args['postal_codes']))       ? array_map('sanitize_text_field', (array) $args['postal_codes'])  : [];
    $priority_listings      = (!empty($args['priority_listings']))  ? array_map('intval', (array) $args['priority_listings'])          : [];

    #$media_tags             = [...$valid_categories, ...$valid_genres, ...$valid_subgenres, ...$valid_instrumentations, ...$valid_settings];
    $page                   = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page              = $page + 1;
    $posts_per_page         = 10;

    $listing_ids = hm_get_listing_ids_by_distance($lat, $lng, $distance, $verified, 'live_music', $postal_codes, $priority_listings);
    if (!empty($args['exclude'])) {
        $listing_ids = array_diff($listing_ids, $args['exclude']);
    }
    if (empty($listing_ids)) {
        return [
            'listings'               => [],
            'valid_categories'       => $valid_categories,
            'valid_genres'           => $valid_genres,
            'valid_subgenres'        => $valid_subgenres,
            'valid_instrumentations' => $valid_instrumentations,
            'valid_settings'         => $valid_settings,
            'valid_ensemble_sizes'   => $valid_ensemble_sizes,
            'max_num_results'        => 0,
            'max_num_pages'          => 0,
            'next_page'              => 1,
        ];
    }

    $query_args = [
        'post_type'      => 'listing',
        'post_status'    => 'publish',
        'paged'          => $page,
        'posts_per_page' => $posts_per_page,
        'post__in'       => $listing_ids,
        'orderby'        => 'post__in',
    ];

    if (!empty($search_term)) {
        $query_args['s'] = $args['search'];
        $query_args['orderby'] = 'relevance';
    }

    if (!empty($name_search_term)) {
        $query_args['meta_query'] = [[
            'key' => 'name',
            'value' => $name_search_term,
            'compare' => 'LIKE',
        ]];
    }

    $tax_queries = [];
    if (!empty($valid_categories)) {
        $tax_queries[] = [
            'taxonomy' => 'mcategory',
            'field' => 'name',
            'terms' => $valid_categories,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_genres)) {
        $tax_queries[] = [
            'taxonomy' => 'genre',
            'field' => 'name',
            'terms' => $valid_genres,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_subgenres)) {
        $tax_queries[] = [
            'taxonomy' => 'subgenre',
            'field' => 'name',
            'terms' => $valid_subgenres,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_instrumentations)) {
        $tax_queries[] = [
            'taxonomy' => 'instrumentation',
            'field' => 'name',
            'terms' => $valid_instrumentations,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_settings)) {
        $tax_queries[] = [
            'taxonomy' => 'setting',
            'field' => 'name',
            'terms' => $valid_settings,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_ensemble_sizes)) {
        $tax_queries[] = [
            'taxonomy' => 'ensemble_size',
            'field' => 'name',
            'terms' => $valid_ensemble_sizes,
            'compare' => 'IN',
        ];
    }
    $query_args['tax_query'] = count($tax_queries) == 0 ? null : (count($tax_queries) == 1 ? [...$tax_queries] : [
        'relation' => 'AND',
        ...$tax_queries,
    ]);

    $query = new WP_Query($query_args);
    global $wpdb;

    // 1. Ensure post meta is primed for all post IDs in the query
    if ($query->have_posts()) {
        $post_ids = wp_list_pluck($query->posts, 'ID');
        update_meta_cache('post', $post_ids);

        // Optional: If you also want to pre-load terms for get_the_terms()
        update_object_term_cache($post_ids, 'genre');
    }

    $max_num_results = $query->found_posts;
    $max_num_pages = $query->max_num_pages;
    $paged = max(1, $query->get('paged'));
    while ($query->have_posts()) {
        $query->the_post();
        $post_id  = get_the_ID();
        $position = (($paged - 1) * $posts_per_page) + $query->current_post + 1;

        // Fetch ALL post meta for this post in 1 array (hits memory cache if primed)
        $meta = get_post_meta($post_id);

        // Helper closure to safely grab single values out of the raw meta array
        // (WordPress get_post_meta arrays wrap values in an array e.g. $meta['key'][0])
        $get_cached_meta = function($key) use ($meta) {
            return isset($meta[$key][0]) ? $meta[$key][0] : '';
        };
        // Helper closure specifically for array-based fields (checkboxes, multi-selects, etc.)
        $get_cached_meta_arr = function($key) use ($meta) {
            if (!isset($meta[$key][0])) {
                return [];
            }
            $val = maybe_unserialize($meta[$key][0]);
            return is_array($val) ? $val : [$val];
        };

        $youtube_video_post_ids = $get_cached_meta_arr('youtube_videos');
        $youtube_video_data = get_youtube_video_data($youtube_video_post_ids);

        $results[] = [
            'post_id'                => $post_id,
            'title'                  => get_the_title(),
            'name'                   => $get_cached_meta('name'),
            'rating'                 => $get_cached_meta('rating'),
            'review_count'           => $get_cached_meta('review_count'),
            'city'                   => $get_cached_meta('city'),
            'state'                  => $get_cached_meta('state'),
            'zip_code'               => $get_cached_meta('zip_code'),
            'description'            => $get_cached_meta('description'),
            'bio'                    => $get_cached_meta('bio'),
            'listing_image_urls'     => get_listing_image_urls($post_id, $get_cached_meta_arr('listing_images')),
            'ensemble_size'          => $get_cached_meta_arr('ensemble_size'),
            'genre'                  => get_the_terms($post_id, 'genre'),
            'thumbnail_url'          => get_the_post_thumbnail_url($post_id, 'standard-listing'),
            'tiny_thumbnail_url'     => get_the_post_thumbnail_url($post_id, 'tiny'),
            'website'                => $get_cached_meta('website'),
            'facebook_url'           => $get_cached_meta('facebook_url'),
            'instagram_url'          => $get_cached_meta('instagram_url'),
            'x_url'                  => $get_cached_meta('x_url'),
            'youtube_url'            => $get_cached_meta('youtube_url'),
            'tiktok_url'             => $get_cached_meta('tiktok_url'),
            'bandcamp_url'           => $get_cached_meta('bandcamp_url'),
            'spotify_artist_url'     => $get_cached_meta('spotify_artist_url'),
            'apple_music_artist_url' => $get_cached_meta('apple_music_artist_url'),
            'soundcloud_url'         => $get_cached_meta('soundcloud_url'),
            'verified'               => $get_cached_meta('verified'),
            'rank'                   => $get_cached_meta('rank'),
            'position'               => $position,
            'youtube_video_data'     => $youtube_video_data,
            'permalink'              => get_permalink(),
            'reviews'                => $with_reviews ? get_reviews('listing_review', $post_id, 1)['reviews'] : [],
        ];
    }

    wp_reset_postdata();
    return [
        'listings'               => $results,
        'valid_categories'       => $valid_categories,
        'valid_genres'           => $valid_genres,
        'valid_subgenres'        => $valid_subgenres,
        'valid_instrumentations' => $valid_instrumentations,
        'valid_settings'         => $valid_settings,
        'valid_ensemble_sizes'   => $valid_ensemble_sizes,
        'max_num_results'        => $max_num_results,
        'max_num_pages'          => $max_num_pages,
        'next_page'              => $next_page,
    ];
}

function validate_tax_input($tax_input, $taxonomy) {
    $input = rest_sanitize_array($tax_input);
    $terms = get_terms_decoded($taxonomy, 'names');
    $valid_input = array_map('stripslashes', array_filter($input, fn($item) => in_array(stripslashes($item), $terms, true)));
    return $valid_input;
}
