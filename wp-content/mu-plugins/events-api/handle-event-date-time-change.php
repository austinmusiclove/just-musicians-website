<?php

function handle_event_date_time_change($event_id) {
    $available_ids   = hm_get_proposal_ids_by_event_id($event_id, ['status' => 'available']);
    $unavailable_ids = hm_get_proposal_ids_by_event_id($event_id, ['status' => 'unavailable']);
    $proposal_ids    = array_merge($available_ids, $unavailable_ids);

    foreach ($proposal_ids as $proposal_id) {
        wp_update_post([
            'ID'         => $proposal_id,
            'meta_input' => [
                'status' => 'stale',
            ],
        ]);
    }
}
