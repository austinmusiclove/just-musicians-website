<?php

function remove_listing_from_collection($collection_id, $listing_id) {

    // Validate collection_id
    if (!is_numeric($collection_id) || !is_numeric($listing_id)) {
        return new WP_Error(400, 'Invalid collection or listing ID');
    }

    // Get Collection
    if ($collection_id != 0) { // 0 signifies favorites
        $collection = get_post($collection_id);
        if (!$collection || $collection->post_type !== 'collection') {
            return new WP_Error(404, 'Collection not found');
        }
    }

    // Get listings
    $listings = [];
    if ($collection_id == 0) {
        $listings = get_user_meta(get_current_user_id(), 'favorites', true);
        $listings = is_array($listings) ? array_map(fn($post_id) => strval($post_id), $listings) : [];
    } else {
        $listings = get_post_meta($collection_id, 'listings', true);
        $listings = is_array($listings) ? array_map(fn($post_id) => strval($post_id), $listings) : [];
    }

    // Remove listing if it is currently in listings
    $filtered = array_filter($listings, fn($id) => (int)$id !== (int)$listing_id);
    if (count($filtered) !== count($listings)) {
        if ($collection_id == 0) {
            $updated = update_user_meta(get_current_user_id(), 'favorites', array_values($filtered));
            if (!$updated) { return new WP_Error(500, 'Failed to update favorites'); }
        } else {
            $updated = update_post_meta($collection_id, 'listings', array_values($filtered));
            if (!$updated) { return new WP_Error(500, 'Failed to update collection'); }
        }
    }

    return new WP_REST_Response(['success' => true, 'listings' => array_values($filtered)], 200);
}
