<?php

function notify_listing_proposal_request($event_id, $listing_ids, $event_name) {
    global $user_messages_plugin;
    if (!$listing_id || !$event_id) { return; }

    // Notify all listing owners via email
    $listing_owners = get_listing_owners($listing_id);
    foreach ($listing_owners as $owner_user_id) {
        send_proposal_request_email($owner_user_id, $event_name);
    }
}
