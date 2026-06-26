<?php

function notify_listing_proposal_request($proposal_id, $listing_id, $event_id) {
    global $user_messages_plugin;
    if (!$listing_id || !$event_id) { return; }

    // Notify all listing owners via email
    $listing_owners = get_listing_owners($listing_id);
    foreach ($listing_owners as $owner_user_id) {
        send_proposal_request_email($owner_user_id, $listing_id, $event_id);
        add_new_inquiry_notification($owner_user_id, $proposal_id);
    }
}
