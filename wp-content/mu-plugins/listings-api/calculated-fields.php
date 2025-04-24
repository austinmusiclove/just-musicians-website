<?php
// Handles populating calculated fields upon every save of a listing post


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// When post gets updated call the calc content api and avoid infinite loop when the post gets updated by the calc api
add_action('save_post_listing', function ($post_id, $post, $update) {
    // delay between scheduling and cron job execution
    $cron_delay = 300; // schedule cron jobs 5 min later so there is time for image processing and other post updates to fully happen and to cut back on the amount of crons happening in an update session

    // Don't run on auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Avoid infinite loop: Only schedule cron if this is not a cron job
    if (did_action('listing_calc_content_cron' . $post_id)) {
        return null;
    } else {

        // Schedle cron if there isn't one already scheduled for this post
        if (!wp_next_scheduled('listing_calc_content_event', [$post_id])) {
            wp_schedule_single_event(time() + $cron_delay, 'listing_calc_content_event', [$post_id]);
        }
    }

    // Avoid infinite loop: Only schedule cron if this is not a cron job
    if (did_action('listing_calc_rank_cron' . $post_id)) {
        return null;
    } else {

        // Schedule a one-time cron job to update the rank
        if (!wp_next_scheduled('listing_calc_rank_event', [$post_id])) {
            wp_schedule_single_event(time() + $cron_delay, 'listing_calc_rank_event', [$post_id]);
        }
    }

}, 10, 3);

// Cron job for updating content
add_action('listing_calc_content_event', function($post_id) {
    do_action('listing_calc_content_cron' . $post_id); // Avoid infinite loop

    // Make sure post exist
    $post = get_post($post_id);
    if (! $post || $post->post_status !== 'publish') { return; }

    // Update the content
    update_post_content($post_id);
});

// Cron job for updating rank
add_action('listing_calc_rank_event', function($post_id) {
    do_action('listing_calc_rank_cron' . $post_id); // Avoid infinite loop

    // Make sure post exist
    $post = get_post($post_id);
    if (! $post || $post->post_status !== 'publish') { return; }

    // Update rank
    update_search_rank($post_id);
});


function update_search_rank($post_id) {
    $rank = 0;

    // Loop through fields to check if they have a value
    $fields_to_check = [
        'name', 'description', 'city', 'state', 'zip_code', 'bio', 'ensemble_size',
        'website', 'instagram_url', 'youtube_url', 'spotify_artist_url',
        'apple_music_artist_url', 'youtube_video_urls',
    ];
    foreach ( $fields_to_check as $field ) {
        if ( ! empty( get_post_meta( $post_id, $field, true ) ) ) {
            $rank++;
        }
    }

    // Boost by admin defined amount
    $boost = get_post_meta( $post_id, 'rank_boost', true );
    $rank += $boost ? $boost : 0;

    $result = update_post_meta( $post_id, 'rank', $rank );
}


function update_post_content($post_id) {
    // Get all meta fields for the post.
    // filter out keys that start with _
    // remove duplicate values
    $meta_fields = array_map(function($value_array) { return array_values(array_unique($value_array)); }, array_filter(get_post_meta( $post_id ), function($key) { return !str_starts_with($key, '_'); }, ARRAY_FILTER_USE_KEY));

    // Get all taxonomy terms for the 'listing' post type.
    $taxonomies = get_object_taxonomies( 'listing' );
    $taxonomy_terms = [];

    foreach ( $taxonomies as $taxonomy ) {
        $terms = wp_get_post_terms( $post_id, $taxonomy );
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
        'ID' => $post_id,
        'post_content' => $combined_content,
    ]);
}
