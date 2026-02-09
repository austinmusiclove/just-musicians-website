<?php
// Handles populating calculated fields upon every save of a listing post


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// When post gets updated call the calc content api and avoid infinite loop when the post gets updated by the calc api
add_action('save_post_listing', function ($post_id, $post, $update) {

    // Don't run on auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Avoid infinite loop: Only schedule cron if this is not an internal calc operation
    if (!did_action('listing_calc' . $post_id)) {

        // Schedle cron if there isn't one already scheduled for this post
        if (!wp_next_scheduled('listing_calc_event', [$post_id])) {
            wp_schedule_single_event(time() + CALC_DELAY, 'listing_calc_event', [$post_id]);
        }
    }

    return null;

}, 10, 3);

// Cron job for updating listing calculated fields
add_action('listing_calc_event', function($post_id) {
    do_action('listing_calc' . $post_id); // Avoid infinite loop

    // Make sure post exist
    $post = get_post($post_id);
    if (! $post || $post->post_status !== 'publish') { return; }

    update_post_content($post_id);
    update_search_rank($post_id);
});

// When a listing review post gets updated call the calc rating api
add_action('save_post_listing_review', function ($post_id, $post, $update) {

    // Don't run on auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Get listing post id
    $listing_post_id = get_post_meta($post_id, 'reviewee', true);

    // Schedle cron if there isn't one already scheduled for this post
    if (!wp_next_scheduled('listing_calc_rating_event', [$listing_post_id])) {
        wp_schedule_single_event(time() + CALC_DELAY, 'listing_calc_rating_event', [$listing_post_id]);
    }

    return null;

}, 10, 3);

// Cron job for updating listing rating
add_action('listing_calc_rating_event', function($listing_post_id) {

    // Make sure post exist
    $post = get_post($listing_post_id);
    if (! $post || $post->post_status !== 'publish') { return; }

    update_listing_rating($listing_post_id);
    update_search_rank($listing_post_id);
});

function update_search_rank($post_id) {
    $rank = 0;

    // Boost rank for every field they have filled out in the listing
    $fields_to_check = [
        'name', 'description', 'city', 'state', 'zip_code', 'bio', 'ensemble_size',
        'website', 'instagram_url', 'youtube_url', 'spotify_artist_url', 'apple_music_artist_url',
        'venues_played_verified', 'venues_played_unverified',
        'listing_images', 'stage_plots', 'youtube_videos', 'verified',
    ];
    foreach ( $fields_to_check as $field ) {
        $meta = get_post_meta( $post_id, $field, true );
        if ( ! empty($meta) ) {

            // Add a point to rank for every image and video upto 3
            if (is_array($meta) and $field == 'listing_images' or $field == 'youtube_videos') {
                $rank += min(count($meta), 3);

            // Add a point for having any value in the field
            } else {
                $rank++;
            }
        }
    }

    // Boost rank for each taxonomy they have at least one term in
    $taxonomies_to_check = [ 'mcategory', 'genre', 'subgenre', 'instrumentation', 'setting', 'keyword', 'mediatag', ];
    foreach ( $taxonomies_to_check as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            $rank++;
        }
    }

    // Boost rank by number of reviews
    $review_count = get_post_meta( $post_id, 'review_count', true );
    $rank += $review_count;

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

function update_listing_rating($listing_id) {
    $listing_id = absint( $listing_id );
    if ( ! $listing_id ) { return; }

    $query = new WP_Query( [
        'post_type'      => 'listing_review',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'reviewee',
                'value' => $listing_id,
            ],
        ],
        'no_found_rows'  => true,
    ] );

    $review_ids = $query->posts;
    $review_count = count( $review_ids );

    if ( $review_count === 0 ) {
        update_post_meta( $listing_id, 'rating', 0 );
        update_post_meta( $listing_id, 'review_count', 0 );
        return;
    }

    $total_rating = 0;
    foreach ( $review_ids as $review_id ) {
        $rating = get_post_meta( $review_id, 'rating', true );
        $rating = floatval( $rating );
        $total_rating += $rating;
    }
    $average_rating = round( $total_rating / $review_count, 1 );

    update_post_meta( $listing_id, 'rating', $average_rating );
    update_post_meta( $listing_id, 'review_count', $review_count );
}
