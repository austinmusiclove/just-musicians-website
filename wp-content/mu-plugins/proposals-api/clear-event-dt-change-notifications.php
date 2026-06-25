<?php

function clear_event_dt_change_notifications($proposal_id) {
    $listing_id = get_post_meta($proposal_id, 'listing', true);

    $owners = get_listing_owners($listing_id);
    foreach ($owners as $user_id) {
        clear_notification($user_id, 'event_dt_change', $proposal_id);
    }
}
