<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_listing($args) {
    $post_id = $args['post_id'];

    // Check if the post ID is valid and if the post exists
    if (empty($post_id) || !is_numeric($post_id)) {
        return new WP_Error('invalid_post_id', 'Invalid post ID.');
    }

    // Retrieve the post object to check if the post exists
    global $post;
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('post_not_found', 'Post not found.');
    }

    // Check if the post is of type 'listing'
    if ($post->post_type !== 'listing') {
        return new WP_Error('invalid_post_type', 'The post is not of type "listing".');
    }

    // Get youtube links
    $youtube_video_post_ids = get_field('youtube_videos');
    $youtube_video_data = get_youtube_video_data($youtube_video_post_ids);

    // Get thumbnail filename
    $thumbnail_filename = '';
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) { $thumbnail_filename = basename(get_attached_file($thumbnail_id)); }

    // Array to store post meta and taxonomy data
    $result = [
        'name'                     => get_field('name'),
        'rating'                   => get_field('rating'),
        'review_count'             => get_field('review_count'),
        'description'              => get_field('description'),
        'city'                     => get_field('city'),
        'state'                    => get_field('state'),
        'zip_code'                 => get_field('zip_code'),
        'bio'                      => get_field('bio'),
        'ensemble_size'            => get_field('ensemble_size'),
        'venues_played_verified'   => wp_objects_to_ids(get_field('venues_played_verified')),
        'venues_played_unverified' => wp_objects_to_ids(get_field('venues_played_unverified')),
        'email'                    => get_field('email'),
        'phone'                    => get_field('phone'),
        'website'                  => get_field('website'),
        'facebook_url'             => get_field('facebook_url'),
        'instagram_handle'         => get_field('instagram_handle'),
        'instagram_url'            => get_field('instagram_url'),
        'x_handle'                 => get_field('x_handle'),
        'x_url'                    => get_field('x_url'),
        'tiktok_handle'            => get_field('tiktok_handle'),
        'tiktok_url'               => get_field('tiktok_url'),
        'youtube_url'              => get_field('youtube_url'),
        'bandcamp_url'             => get_field('bandcamp_url'),
        'spotify_artist_url'       => get_field('spotify_artist_url'),
        'spotify_artist_id'        => get_field('spotify_artist_id'),
        'apple_music_artist_url'   => get_field('apple_music_artist_url'),
        'soundcloud_url'           => get_field('soundcloud_url'),
        'verified'                 => get_field('verified'),
        'youtube_video_data'       => $youtube_video_data,
        'thumbnail_filename'       => $thumbnail_filename,
        'thumbnail_id'             => $thumbnail_id,
        'thumbnail_url'            => get_the_post_thumbnail_url($post_id, 'standard-listing'),
        'thumbnail_terms'          => get_mediatags(get_post_thumbnail_id($post_id)),
        'listing_images'           => get_field('listing_images'),
        'stage_plots'              => get_field('stage_plots'),
        'listing_images_data'      => get_image_data($post_id, 'listing_images'),
        'stage_plots_data'         => get_image_data($post_id, 'stage_plots'),
        'permalink'                => get_permalink($post_id),
        'post_status'              => get_post_status($post_id),
    ];

    // Get all taxonomies associated with the post
    $taxonomies = get_object_taxonomies('listing', 'objects');

    // Add taxonomy data to the array
    foreach ($taxonomies as $taxonomy) {
        $terms = wp_get_post_terms($post_id, $taxonomy->name);

        // If terms exist for this taxonomy, add them to the result array
        if (!is_wp_error($terms) && !empty($terms)) {
            $result[$taxonomy->name] = array();
            foreach ($terms as $term) {
                $result[$taxonomy->name][] = $term->name; // Store the term name
            }
        }
    }


    // Return the complete data
    wp_reset_postdata();
    return $result;
}


function get_listings_by_auuid($auuid) {
    $listings = [];
    $query = new WP_Query(array(
        'post_type'   => 'listing',
        'post_status' => ['publish', 'draft'],
        'meta_query'  => [
            [
                'key' => 'artist_uuid',
                'value' => $auuid,
                'compare' => '=',
            ]
        ],
    ));

    if ($query->have_posts()) {
        while($query->have_posts()) {
            $query->the_post();
            $listings[] = [
                'id'     => get_the_ID(),
                'title'  => get_the_title(),
                'status' => get_post_status(get_the_ID()),
            ];
        }
    }

    wp_reset_postdata();
    return $listings;
}

function get_image_data($post_id, $image_field) {
    $images = [];

    // Get post meta which contains an array of attachment IDs (assuming it's stored that way)
    $attachment_ids = get_post_meta($post_id, $image_field, true);

    if (!is_array($attachment_ids)) {
        $attachment_ids = [$attachment_ids];
    }

    foreach ($attachment_ids as $index => $attachment_id) {
        if (!$attachment_id || !get_post($attachment_id)) {
            continue; // skip invalid or non-existent attachments
        }

        $images[] = [
            'image_id'      => strval($attachment_id),
            'attachment_id' => strval($attachment_id),
            'url'           => wp_get_attachment_url($attachment_id),
            'filename'      => basename(get_attached_file($attachment_id)),
            'caption'       => get_the_excerpt($attachment_id),
            'mediatags'     => get_mediatags($attachment_id),
            'loading'       => false,
            'worker'        => null,
        ];
    }

    return $images;
}

function get_youtube_video_data($post_ids) {
    if (!is_array($post_ids)) { return []; }
    $posts = [];

    foreach ($post_ids as $video_post_id) {
        $posts[] = [
            'post_id'    => $video_post_id,
            'url'        => get_post_meta($video_post_id, 'url', true),
            'video_id'   => get_post_meta($video_post_id, 'video_id', true),
            'start_time' => get_post_meta($video_post_id, 'start_time', true),
            'mediatags'  => get_mediatags($video_post_id),
        ];
    }

    return $posts;
}

function get_mediatags($attachment_id) {
    $mediatags = [];
    $terms = get_the_terms($attachment_id, 'mediatag');
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) { $mediatags[] = str_replace("\'", "'", $term->name); }
    }
    return $mediatags;
}
