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

    update_search_rank($listing_posts);
    update_post_content($listing_posts);

    return new WP_REST_Response( 'Listing posts updated', 200 );
}
function update_search_rank($posts) {
    foreach( $posts as $post) {
        $rank = 0;

        // Loop through fields to check if they have a value
        $fields_to_check = [
            'name', 'description', 'city', 'state', 'zip_code', 'bio', 'ensemble_size', 'draw', 'email', 'phone',
            'website', 'instagram_handle', 'instagram_url', 'youtube_url', 'spotify_artist_url', 'spotify_artist_id',
            'apple_music_artist_url', 'youtube_video_urls', 'venues_played_verified'
        ];
        foreach ( $fields_to_check as $field ) {
            if ( ! empty( get_post_meta( $post->ID, $field, true ) ) ) {
                $rank++;
            }
        }

        update_post_meta( $post->ID, 'rank', $rank );
    }
}
function update_post_content($posts) {
    foreach ( $posts as $post ) {
        // Get all meta fields for the post.
        // filter out keys that start with _
        // remove duplicate values
        $meta_fields = array_map(function($value_array) { return array_values(array_unique($value_array)); }, array_filter(get_post_meta( $post->ID ), function($key) { return !str_starts_with($key, '_'); }, ARRAY_FILTER_USE_KEY));

        // Get all taxonomy terms for the 'listing' post type.
        $taxonomies = get_object_taxonomies( 'listing' );
        $taxonomy_terms = [];

        foreach ( $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post->ID, $taxonomy );
            foreach ( $terms as $term ) {
                $taxonomy_terms[] = $term->name;
            }
        }

        // Combine meta fields and taxonomy terms into a string.
        $content_parts = [];

        // Add meta field data to content.
        foreach ( $meta_fields as $key => $values ) {
            $values = array_unique($values);
            foreach ( $values as $value ) {
                $content_parts[] = sanitize_text_field($value);  // You can customize this depending on your needs.
            }
        }

        // Add taxonomy terms to content.
        if ( ! empty( $taxonomy_terms ) ) {
            $content_parts = array_merge( $content_parts, $taxonomy_terms );
        }

        // Combine all parts into one string and update the content.
        $combined_content = implode( ' ', $content_parts );
        wp_update_post( [
            'ID' => $post->ID,
            'post_content' => $combined_content,
        ]);
    }
}
