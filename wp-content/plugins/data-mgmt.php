<?php
/**
 * Plugin Name: Just Musicians Data Management API
 * Description: A custom plugin to expose REST APIs for doing data operations.
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register REST API Routes
function custom_listing_api_routes() {
    register_rest_route( 'datamgmt/v1', '/set-status', [
        'methods' => 'POST',
        'callback' => 'set_listing_status_to_complete',
    ]);

    register_rest_route( 'datamgmt/v1', '/update-content', [
        'methods' => 'POST',
        'callback' => 'update_listing_content_with_meta_and_taxonomy',
    ]);

    register_rest_route( 'datamgmt/v1', '/update-tiktok-url', [
        'methods' => 'POST',
        'callback' => 'update_listing_tiktok_url',
    ]);
}

add_action( 'rest_api_init', 'custom_listing_api_routes' );

// Function 1: Set 'status' meta key to 'Complete' for all 'listing' posts.
function set_listing_status_to_complete() {
    $args = [
        'post_type' => 'listing',
        'posts_per_page' => -1,  // Get all posts.
        'post_status' => 'publish', // You can modify this to include drafts if needed.
    ];

    $listing_posts = get_posts( $args );

    foreach ( $listing_posts as $post ) {
        update_post_meta( $post->ID, 'status', 'Complete' );
    }

    return new WP_REST_Response( 'Status set to Complete for all listings.', 200 );
}

// Function 2: Combine meta fields and taxonomy terms into post content.
function update_listing_content_with_meta_and_taxonomy() {
    $args = [
        'post_type' => 'listing',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $listing_posts = get_posts( $args );

    foreach ( $listing_posts as $post ) {
        // Get all meta fields for the post.
        $meta_fields = get_post_meta( $post->ID );

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

    return new WP_REST_Response( 'Content updated for all listings with meta and taxonomy terms.', 200 );
}

// Function 3: Update TikTok URL.
function update_listing_tiktok_url() {
    $args = [
        'post_type' => 'listing',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $listing_posts = get_posts( $args );

    foreach ( $listing_posts as $post ) {
        $tiktok_handle = get_post_meta( $post->ID, 'tiktok_handle', true );

        if ( $tiktok_handle ) {
            $tiktok_url = 'https://www.tiktok.com/@' . $tiktok_handle;
            update_post_meta( $post->ID, 'tiktok_url', $tiktok_url );
        }
    }

    return new WP_REST_Response( 'TikTok URL updated for all listings.', 200 );
}

