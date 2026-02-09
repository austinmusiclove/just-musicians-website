<?php
// Handles populating calculated fields for venue posts


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// When post gets updated call the calc content api and avoid infinite loop when the post gets updated by the calc api
add_action('save_post_comp_report', function ($post_id, $post, $update) {

    // Don't run on auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Make sure post is published
    if (! $post || $post->post_status !== 'publish') { return; }

    // Get venue post id
    $venue_post_id = get_post_meta($post_id, 'venue_post_id', true);

    // Schedule a one-time cron job to update venue stats
    if (!wp_next_scheduled('venue_stats_calc_event', [$venue_post_id])) {
        wp_schedule_single_event(time() + CALC_DELAY, 'venue_stats_calc_event', [$venue_post_id]);
    }

    return null;

}, 10, 3);

// When post gets updated call the calc content api and avoid infinite loop when the post gets updated by the calc api
add_action('save_post_venue_review', function ($post_id, $post, $update) {

    // Don't run on auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Make sure post is published
    if (! $post || $post->post_status !== 'publish') { return; }

    // Get venue post id
    $venue_post_id = get_post_meta($post_id, 'reviewee', true);

    // Schedule a one-time cron job to update venue stats
    if (!wp_next_scheduled('venue_stats_calc_event', [$venue_post_id])) {
        wp_schedule_single_event(time() + CALC_DELAY, 'venue_stats_calc_event', [$venue_post_id]);
    }

    return null;

}, 10, 3);

// Cron job for updating venue stats
add_action('venue_stats_calc_event', function($venue_post_id) {

    // Make sure post exist
    $post = get_post($venue_post_id);
    if (! $post || $post->post_status !== 'publish') { return; }

    // Update rank
    update_venue_stats($venue_post_id);
});
