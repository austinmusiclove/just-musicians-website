<?php

function get_users_saved_listings() {
    $saved_listings = [];

    if (is_user_logged_in()) {
        $result = get_collections([
            'nopaging'     => true,
            'nothumbnails' => true,
        ]);
        foreach($result['collections'] as $collection) {
            foreach($collection['listings'] as $listing_id) {
                $saved_listings[] = $listing_id;
            }
        }
    }

    return $saved_listings;
}
