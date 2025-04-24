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

    // If the post does not exist, return an error
    if (!$post) {
        return new WP_Error('post_not_found', 'Post not found.');
    }

    // Check if the post is of type 'listing'
    if ($post->post_type !== 'listing') {
        return new WP_Error('invalid_post_type', 'The post is not of type "listing".');
    }

    // Get all post meta fields
    $post_meta = get_post_meta($post_id);

    // Get youtube links
    $youtube_video_urls = get_field('youtube_video_urls');
    $youtube_video_ids = [];
    if ($youtube_video_urls and is_array($youtube_video_urls)) {
        foreach($youtube_video_urls as $url) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/.+\/|\S+\?)(?:[^&]*&)*v=|youtu\.be\/)([a-zA-Z0-9_-]{11})(?=&|$)/', $url, $matches)) { $youtube_video_ids[] = $matches[1]; }
        }
    }

    // Array to store post meta and taxonomy data
    $result = [
        'name'                   => get_field('name'),
        'description'            => get_field('description'),
        'city'                   => get_field('city'),
        'state'                  => get_field('state'),
        'zip_code'               => get_field('zip_code'),
        'bio'                    => get_field('bio'),
        'ensemble_size'          => get_field('ensemble_size'),
        'email'                  => get_field('email'),
        'phone'                  => get_field('phone'),
        'website'                => get_field('website'),
        'facebook_url'           => get_field('facebook_url'),
        'instagram_handle'       => get_field('instagram_handle'),
        'instagram_url'          => get_field('instagram_url'),
        'x_handle'               => get_field('x_handle'),
        'x_url'                  => get_field('x_url'),
        'tiktok_handle'          => get_field('tiktok_handle'),
        'tiktok_url'             => get_field('tiktok_url'),
        'youtube_url'            => get_field('youtube_url'),
        'bandcamp_url'           => get_field('bandcamp_url'),
        'spotify_artist_url'     => get_field('spotify_artist_url'),
        'apple_music_artist_url' => get_field('apple_music_artist_url'),
        'soundcloud_url'         => get_field('soundcloud_url'),
        'verified'               => get_field('verified'),
        'youtube_video_urls'     => $youtube_video_urls,
        'youtube_video_ids'      => $youtube_video_ids,
        'thumbnail_url'          => get_the_post_thumbnail_url($post_id, 'standard-listing'),
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
        'post_type'      => 'listing',
        'meta_query' => [
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
                'id'    => get_the_ID(),
                'title' => get_the_title(),
            ];
        }
    }

    return $listings;
}
