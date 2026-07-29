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
add_action('delete_post', 'delete_listing_post_hook');
function delete_listing_post_hook($post_id) {
    if (get_post_type($post_id) !== 'listing') { return; }

    // 1. Clean up user favorites (usermeta 'listings')
    $users = get_users(['fields' => ['ID']]);
    foreach ($users as $user) {
        $favorites = get_user_meta($user->ID, 'favorites', true);
        if (!is_array($favorites)) continue;

        $new_favorites = array_filter($favorites, fn($id) => intval($id) !== intval($post_id));
        if ($new_favorites !== $favorites) {
            update_user_meta($user->ID, 'favorites', $new_favorites);
        }

        $user_listings = get_user_meta($user->ID, 'listings', true);
        if (!is_array($user_listings)) continue;

        $new_favorites = array_filter($user_listings, fn($id) => intval($id) !== intval($post_id));
        if ($new_favorites !== $user_listings) {
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

    // 3. Delete all application submissions for this listing
    $submissions = get_posts([
        'post_type'      => 'app_submission',
        'post_status'    => ['publish', 'draft', 'pending', 'future', 'private', 'trash'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'listing',
                'value' => intval($post_id),
            ],
        ],
    ]);

    foreach ($submissions as $submission_id) {
        wp_delete_post($submission_id, true);
    }

    // 4. Delete all proposals for this listing
    $proposals = get_posts([
        'post_type'      => 'proposal',
        'post_status'    => ['publish', 'draft', 'pending', 'future', 'private', 'trash'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'listing',
                'value' => intval($post_id),
            ],
        ],
    ]);

    foreach ($proposals as $proposal_id) {
        wp_delete_post($proposal_id, true);
    }
}

add_action('wp_trash_post', 'trash_listing_post_hook');
function trash_listing_post_hook($post_id) {
    if (get_post_type($post_id) !== 'listing') { return; }

    // 1. Trash all application submissions for this listing
    $submissions = get_posts([
        'post_type'      => 'app_submission',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'listing',
                'value' => intval($post_id),
            ],
        ],
    ]);

    foreach ($submissions as $submission_id) {
        wp_trash_post($submission_id);
    }

    // 2. Trash all proposals for this listing
    $proposal_ids = hm_get_proposals_by_listing_ids([$post_id]);
    foreach ($proposal_ids as $proposal_id) {
        wp_trash_post($proposal_id);
    }
}
