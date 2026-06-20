<?php

// Update Event
$args = get_sanitized_event_args(true);
$args['ID'] = get_query_var('event-id');

$result = update_user_event($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Event Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-event\', { \'event\': ' . clean_arr_for_doublequotes($result) . ' })"></span>';
