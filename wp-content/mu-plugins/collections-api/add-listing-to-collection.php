<?php

function add_listing_to_collection($collection_id, $listing_id) {

    // Validate collection_id
    if (!is_numeric($collection_id) || !is_numeric($listing_id)) {
        //return new WP_Error(400, 'Invalid collection or listing ID');
        return new WP_Error(400, 'Invalid collection or listing ID :: ' . $collection_id . ' :: ' . $listing_id);
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

    // Add listing if not in listings
    if (!in_array($listing_id, $listings)) {
        $listings[] = $listing_id;
        if ($collection_id == 0) {
            $updated = update_user_meta(get_current_user_id(), 'favorites', array_values($listings));
            if (!$updated) { return new WP_Error(500, 'Failed to update favorites'); }
        } else {
            $updated = update_post_meta($collection_id, 'listings', array_values($listings));
            if (!$updated) { return new WP_Error(500, 'Failed to update collection'); }
        }
    }

    return new WP_REST_Response(['success' => true, 'listings' => $listings], 200);
}

