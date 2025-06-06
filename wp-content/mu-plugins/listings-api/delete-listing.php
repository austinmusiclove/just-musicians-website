<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _delete_listing($args) {

    if (empty($args['post_id'])) {
        return new WP_Error('missing_post_id', 'Cannot delete listing without post ID.', ['status' => 400]);
    }

    $post_id = intval($args['post_id']);
    $post = get_post($post_id);

    if (!$post) {
        return new WP_Error('not_found', 'Listing not found.', ['status' => 404]);
    }

    if ($post->post_type !== 'listing') {
        return new WP_Error('invalid_type', 'Post is not a listing.', ['status' => 400]);
    }

    $result = wp_trash_post($post_id);

    if (!$result || is_wp_error($result)) {
        return new WP_Error('delete_failed', 'Failed to delete listing.', ['status' => 500]);
    }

    return true;
}

// After listing deletion, delete all references of the listing in collections and user favorites
add_action('delete_post', 'delete_post_hook');
function delete_post_hook($post_id) {
    if (get_post_type($post_id) !== 'listing') { return; }

    // Schedle cron if there isn't one already scheduled for this post
    if (!wp_next_scheduled('clean_listing_references_after_delete', [$post_id])) {
        wp_schedule_single_event(time() + LISTING_CALC_DELAY, 'clean_listing_references_after_delete', [$post_id]);
    }
}
add_action('clean_listing_references_after_delete', function($post_id) {

    // 1. Clean up user favorites (usermeta 'listings')
    $users = get_users(['fields' => ['ID']]);
    foreach ($users as $user) {
        $favorites = get_user_meta($user->ID, 'listings', true);
        if (!is_array($favorites)) continue;

        $new_favorites = array_filter($favorites, fn($id) => intval($id) !== intval($post_id));
        if ($new_favorites !== $favorites) {
            update_user_meta($user->ID, 'listings', $new_favorites);
        }
    }

    // 2. Clean up collection posts (postmeta 'listings')
    $collections = get_posts([
        'post_type'      => 'collection',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ($collections as $collection_id) {
        $listing_ids = get_post_meta($collection_id, 'listings', true);
        if (!is_array($listing_ids)) continue;

        $new_listings = array_filter($listing_ids, fn($id) => intval($id) !== intval($post_id));
        if ($new_listings !== $listing_ids) {
            update_post_meta($collection_id, 'listings', $new_listings);
        }
    }
});
