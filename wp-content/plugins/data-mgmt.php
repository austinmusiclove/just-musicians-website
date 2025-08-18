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
        'callback' => 'print_listings_youtube_urls',
        'permission_callback' => 'is_admin_jwt',
    ]);
    register_rest_route( 'datamgmt/v1', '/unassigned-listings', [
        'methods' => 'GET',
        'callback' => 'get_unassigned_listings',
        'permission_callback' => '__return_true',
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
function print_listings_youtube_urls() {
    $listing_ids = get_posts([
        'post_type'      => 'listing',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);

    foreach ($listing_ids as $post_id) {
        print_listing_youtube_urls($post_id);
    }
}
function print_listing_youtube_urls($post_id) {
    $youtube_urls = get_post_meta($post_id, 'youtube_video_urls', true);
    if (empty($youtube_urls) || !is_array($youtube_urls)) { return; }

    foreach ($youtube_urls as $url) {
        error_log($url);
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
        }
    }

    // Update the listing's youtube_videos field with the array of post IDs
    update_post_meta($post_id, 'youtube_videos', $youtube_post_ids);
}

function get_unassigned_listings() {
    global $wpdb;

    // Step 1: Get all published listing IDs and titles
    $listings = $wpdb->get_results("
        SELECT ID, post_title, post_status
        FROM {$wpdb->posts}
        WHERE post_type = 'listing' AND post_status = 'publish'
    ");

    // Step 2: Get all usermeta values for the 'listings' field
    $usermeta = $wpdb->get_col("
        SELECT meta_value
        FROM {$wpdb->usermeta}
        WHERE meta_key = 'listings'
    ");

    // Step 3: Extract all listing IDs from serialized data
    $assigned_listing_ids = [];

    foreach ($usermeta as $meta_value) {
        $ids = maybe_unserialize($meta_value);
        if (is_array($ids)) {
            $assigned_listing_ids = array_merge($assigned_listing_ids, $ids);
        }
    }

    $assigned_listing_ids = array_map('intval', array_unique($assigned_listing_ids));

    // Step 4: Filter out listings that are assigned
    $unassigned = [];

    foreach ($listings as $listing) {
        if (!in_array((int)$listing->ID, $assigned_listing_ids, true)) {
            $auuid = get_post_meta($listing->ID, 'artist_uuid', true);
            $unassigned[] = [
                'ID' => (int)$listing->ID,
                'title' => $listing->post_title,
                'status' => $listing->post_status,
                'artist_auuid' => $auuid,
            ];
        }
    }

    return $unassigned;
}

