<?php

function handle_event_date_time_change($event_id) {
    $available_ids   = hm_get_proposal_ids_by_event_id($event_id, ['status' => 'available']);
    $unavailable_ids = hm_get_proposal_ids_by_event_id($event_id, ['status' => 'unavailable']);
    $proposal_ids    = array_merge($available_ids, $unavailable_ids);

    foreach ($proposal_ids as $proposal_id) {
        $listing_id = get_post_meta($proposal_id, 'listing', true);

        // Set proposal status to stale
        $result = wp_update_post([
            'ID'         => $proposal_id,
            'meta_input' => [
                'status' => 'stale',
            ],
        ], true);
        if (is_wp_error($result) || !$result) {
            error_log('handle_event_date_time_change: failed to update proposal ' . $proposal_id . ' to stale — ' . (is_wp_error($result) ? $result->get_error_message() : 'wp_update_post returned false'));
        }

        $owners = get_listing_owners($listing_id);
        foreach ($owners as $user_id) {
            notify_date_time_change($event_id, $listing_id, $user_id, $proposal_id);
        }
    }
}

function notify_date_time_change($event_id, $listing_id, $user_id, $proposal_id) {
    send_proposal_date_time_change_email($user_id, $listing_id, $event_id);
    add_event_dt_change_notification($user_id, $proposal_id);
}
