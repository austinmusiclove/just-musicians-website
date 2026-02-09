<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_listings_by_id($args) {
    $results = [];
    $listing_ids    = (!empty($args['listing_ids'])) ? rest_sanitize_array($args['listing_ids']) : [];
    $sanitized_page = (!empty($args['page']))        ? sanitize_text_field($args['page'])        : null;
    $nopaging       = (!empty($args['nopaging']))    ? rest_sanitize_boolean($args['nopaging'])  : false;
    $page = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page       = $page + 1;
    $max_num_results = 0;
    $max_num_pages   = 1;

    if (count($listing_ids) > 0) {

        $query_args = [
            'post_type'      => 'listing',
            'post_status'    => 'publish',
            'post__in'       => $listing_ids,
            'orderby'        => 'post__in',
        ];
        if ($nopaging) {
            $query_args['nopaging'] = true;
        } else {
            $query_args['paged']          = $page;
            $query_args['posts_per_page'] = 10;
        }
        $query = new WP_Query($query_args);
        $max_num_results = $query->found_posts;
        $max_num_pages = $query->max_num_pages;
        while ($query->have_posts()) {
            $query->the_post();

            // Get youtube links
            $youtube_video_post_ids = get_field('youtube_videos');
            $youtube_video_data = get_youtube_video_data($youtube_video_post_ids);

            $results[] = [
                'post_id'                => get_the_ID(),
                'title'                  => get_the_title(),
                'name'                   => get_field('name'),
                'rating'                 => get_field('rating'),
                'review_count'           => get_field('review_count'),
                'city'                   => get_field('city'),
                'state'                  => get_field('state'),
                'description'            => get_field('description'),
                'genre'                  => get_the_terms(get_the_ID(), 'genre'),
                'thumbnail_url'          => get_the_post_thumbnail_url(get_the_ID(), 'standard-listing'),
                'website'                => get_field('website'),
                'facebook_url'           => get_field('facebook_url'),
                'instagram_url'          => get_field('instagram_url'),
                'x_url'                  => get_field('x_url'),
                'youtube_url'            => get_field('youtube_url'),
                'tiktok_url'             => get_field('tiktok_url'),
                'bandcamp_url'           => get_field('bandcamp_url'),
                'spotify_artist_url'     => get_field('spotify_artist_url'),
                'apple_music_artist_url' => get_field('apple_music_artist_url'),
                'soundcloud_url'         => get_field('soundcloud_url'),
                'verified'               => get_field('verified'),
                'youtube_video_data'     => $youtube_video_data,
                'permalink'              => get_permalink(),
            ];
        }
        wp_reset_postdata();

    }

    return [
        'listings'        => $results,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}
