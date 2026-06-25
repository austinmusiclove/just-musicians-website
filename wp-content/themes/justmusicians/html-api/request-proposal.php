<?php

$event_id = get_query_var('event-id');
$listing_id = get_query_var('listing-id');

// Check if user is authorized
$is_authorized = user_owns_event($event_id);
if ( is_wp_error($is_authorized)) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Request proposal
$result = request_proposal($event_id, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Invite Sent Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'add-listing-to-event\', {\'event_id\': \'' . $event_id . '\', \'listing_id\': \'' . $listing_id . '\' })"></span>';
