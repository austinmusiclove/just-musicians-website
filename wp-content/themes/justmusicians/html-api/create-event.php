<?php

// Create Event
$args = get_sanitized_event_args(false);

$result = create_event($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="_handleCreateInquiryError(\'' . $message . '\')"></span>';
    exit;
}

// Success Response
echo '<span x-init="_handleCreateInquirySuccess(\'' . $result['permalink'] . '\')"></span>';
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Event Created Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'add-event\', {\'post_id\': \'' . $result['post_id'] . '\', \'event_name\': \'' . $result['event_name'] . '\', \'listings\': ' . clean_arr_for_doublequotes($result['listings']) . ', \'permalink\': \'' . $result['permalink'] . '\' })"></span>';
