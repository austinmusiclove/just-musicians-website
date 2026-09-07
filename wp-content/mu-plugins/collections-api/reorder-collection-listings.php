<?php

function reorder_collection_listings($collection_id, $ordered_ids, $start_index = 0) {

    // Validate collection_id
    if (!is_numeric($collection_id)) {
        return new WP_Error(400, 'Invalid collection ID :: ' . $collection_id);
    }

    // Validate ordered_ids
    if (!is_array($ordered_ids)) {
        return new WP_Error(400, 'Invalid listing IDs');
    }
    $ordered_ids = array_values(array_unique(array_filter(array_map('intval', $ordered_ids))));

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
        $listings = is_array($listings) ? array_map('intval', $listings) : [];
    } else {
        $listings = get_post_meta($collection_id, 'listings', true);
        $listings = is_array($listings) ? array_map('intval', $listings) : [];
    }

    // Merge: keep the current list up to the starting index, then the submitted
    // order, then the remaining current ids (in their original relative order)
    // that are not already present in the submitted list.
    $start_index = min(max(0, (int) $start_index), count($listings));
    $prefix      = array_slice($listings, 0, $start_index);
    $reordered   = array_values(array_intersect($ordered_ids, $listings));
    $remaining   = array_values(array_diff($listings, array_merge($prefix, $reordered)));
    $merged      = array_merge($prefix, $reordered, $remaining);

    // Persist only when the order actually changed
    $merged_strvals = array_map('strval', $merged);
    if (array_values($merged_strvals) !== array_values($orig)) {
        if ($collection_id == 0) {
            $updated = update_user_meta(get_current_user_id(), 'favorites', array_values($merged_strvals));
            if (!$updated) { return new WP_Error(500, 'Failed to update favorites'); }
        } else {
            $updated = update_post_meta($collection_id, 'listings', array_values($merged_strvals));
            if ($updated === false) { return new WP_Error(500, 'Failed to update collection'); }
        }
    }

    return new WP_REST_Response(['success' => true, 'listings' => $merged_strvals], 200);
}
