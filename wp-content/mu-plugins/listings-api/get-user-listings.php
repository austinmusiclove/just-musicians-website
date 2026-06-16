<?php

function get_user_listings($user_id) {
    $listing_ids = get_user_meta($user_id, 'listings', true);
    if (empty($listing_ids) || !is_array($listing_ids)) {
        return [];
    }
    $listings = [];
    foreach ($listing_ids as $id) {
        if (get_post_status($id) !== 'publish') {
            continue;
        }
        $name = get_post_meta($id, 'name', true);
        if ($name) {
            $listings[$id] = $name;
        }
    }
    return $listings;
}
