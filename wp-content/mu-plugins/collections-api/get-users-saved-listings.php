<?php

function get_users_saved_listings() {
    $result = [
        'collections'        => [],
        'all_saved_listings' => [],
    ];

    if (is_user_logged_in()) {
        $collections_result = get_collections([
            'nopaging'     => true,
            'nothumbnails' => true,
        ]);
        $result['collections'] = $collections_result['collections'];

        $listing_ids_set = [];
        foreach($collections_result['collections'] as $collection) {
            foreach($collection['listings'] as $listing_id) {
                $listing_ids_set[$listing_id] = true;
            }
        }

        $result['all_saved_listings'] = array_map('strval', array_keys($listing_ids_set));
    }

    return $result;
}
