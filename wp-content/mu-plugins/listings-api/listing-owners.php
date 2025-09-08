<?php

function get_listing_owners($listing_id) {
    global $wpdb;
    $confirmed_users = [];

    if (empty($listing_id) || !is_numeric($listing_id)) {
        return [];
    }

    // Prepare the serialized search pattern
    $listing_id = (int) $listing_id;
    $pattern = '%' . $listing_id . '%';

    // Query: Get user ID where meta_key = 'listings' and value matches the given id
    $query = $wpdb->prepare("
        SELECT user_id, meta_value
        FROM {$wpdb->usermeta}
        WHERE meta_key = 'listings'
        AND meta_value LIKE %s
    ", $pattern);

    $results = $wpdb->get_results($query);

    foreach ($results as $row) {
        $listings = maybe_unserialize($row->meta_value);

        if (is_array($listings) && in_array((int)$listing_id, array_map('intval', $listings))) {
            $confirmed_users[] = (int) $row->user_id;
        }
    }

    return $confirmed_users;
}
