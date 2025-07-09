<?php
/**
 * Plugin Name: Just Musicians Data Management API
 * Description: A custom plugin to expose REST APIs for doing data operations
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'datamgmt/v1', '/listing-metadata', [
        'methods' => 'POST',
        'callback' => 'update_listings_meta_data',
        'permission_callback' => 'is_admin_jwt',
    ]);
    register_rest_route( 'datamgmt/v1', '/listing-youtube-posts', [
        'methods' => 'POST',
        'callback' => 'update_listings_youtube_videos',
        'permission_callback' => 'is_admin_jwt',
    ]);
});


// Updates content so that listings are searchable by all metafields and taxonomies
// Updates search rank
function update_listings_meta_data() {
    $listing_posts = get_posts( [
        'post_type' => 'listing',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    update_search_rank_multiple($listing_posts);
    update_post_content_multiple($listing_posts);

    return new WP_REST_Response( 'Listing posts updated', 200 );
}


function update_search_rank_multiple($posts) {
    foreach( $posts as $post) {
        do_action('listing_calc_rank' . $post->ID); // Avoid infinite loop
        update_search_rank($post->ID);
    }
}
function update_post_content_multiple($posts) {
    foreach ( $posts as $post ) {
        do_action('listing_calc_content' . $post->ID); // Avoid infinite loop
        update_post_content($post->ID);
    }
}


// Create youtube posts from youtube urls
function update_listings_youtube_videos() {
    $listing_ids = get_posts([
        'post_type'      => 'listing',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);

    foreach ($listing_ids as $post_id) {
        update_listing_youtube_videos($post_id);
    }
}

function update_listing_youtube_videos($post_id) {
    $youtube_post_ids = [];
    $youtube_urls = get_post_meta($post_id, 'youtube_video_urls', true);

    if (empty($youtube_urls) || !is_array($youtube_urls)) {
        return;
    }

    foreach ($youtube_urls as $url) {

        // Create the YouTube video post if video id can be found
        if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/.+\/|\S+\?)(?:[^&]*&)*v=|youtu\.be\/)([a-zA-Z0-9_-]{11})(?=&|$)/', $url, $matches)) {
            $video_id = $matches[1];
            /*
            $new_post_id = wp_insert_post([
                'post_type'   => 'youtubevideo',
                'post_status' => 'publish',
                'post_title'  => $url,
                'meta_input'  => [
                    'url'        => $url,
                    'video_id'   => $video_id,
                    'start_time' => 0,
                ],
            ]);

            if (!is_wp_error($new_post_id)) {
                $youtube_post_ids[] = $new_post_id;
            }
            */
        } else {
            error_log($post_id . ' :: ' . $url);
        }
    }

    // Update the listing's youtube_videos field with the array of post IDs
    //update_post_meta($post_id, 'youtube_videos', $youtube_post_ids);
}

