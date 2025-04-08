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

    // Get all taxonomies associated with the post
    $taxonomies = get_object_taxonomies(get_post_type($post_id), 'objects');

    // Array to store post meta and taxonomy data
    $result = array();

    // Add post meta data to the array
    $result['post_meta'] = $post_meta;

    // Add thumbnail url
    $result['thumbnail_url'] = get_the_post_thumbnail_url($post);

    // Add taxonomy data to the array
    foreach ($taxonomies as $taxonomy) {
        $terms = wp_get_post_terms($post_id, $taxonomy->name);

        // If terms exist for this taxonomy, add them to the result array
        if (!is_wp_error($terms) && !empty($terms)) {
            $result['taxonomies'][$taxonomy->name] = array();
            foreach ($terms as $term) {
                $result['taxonomies'][$taxonomy->name][] = $term->name; // Store the term name
            }
        }
    }

    // Return the complete data
    return $result;
}
