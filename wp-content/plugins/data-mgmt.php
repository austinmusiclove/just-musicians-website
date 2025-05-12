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
