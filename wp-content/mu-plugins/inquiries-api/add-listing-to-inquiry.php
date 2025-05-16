<?php

function add_listing_to_inquiry($inquiry_id, $listing_id) {

    // Validate inquiry_id
    if (!is_numeric($inquiry_id) || !is_numeric($listing_id)) {
        //return new WP_Error(400, 'Invalid inquiry or listing ID');
        return new WP_Error(400, 'Invalid inquiry or listing ID :: ' . $inquiry_id . ' :: ' . $listing_id);
    }

    // Get Inquiry
    if ($inquiry_id != 0) { // 0 signifies favorites
        $inquiry = get_post($inquiry_id);
        if (!$inquiry || $inquiry->post_type !== 'inquiry') {
            return new WP_Error(404, 'Inquiry not found');
        }
    }

    // Get listings
    $listings = [];
    $listings = get_post_meta($inquiry_id, 'listings', true);
    $listings = is_array($listings) ? array_map(fn($post_id) => strval($post_id), $listings) : [];

    // Add listing if not in listings
    if (!in_array($listing_id, $listings)) {
        $listings[] = $listing_id;
        if ($inquiry_id == 0) {
            $updated = update_user_meta(get_current_user_id(), 'favorites', array_values($listings));
            if (!$updated) { return new WP_Error(500, 'Failed to update favorites'); }
        } else {
            $updated = update_post_meta($inquiry_id, 'listings', array_values($listings));
            if (!$updated) { return new WP_Error(500, 'Failed to update inquiry'); }
        }
    }

    return new WP_REST_Response(['success' => true, 'listings' => $listings], 200);
}

