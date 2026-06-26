<?php

// Create Event
$args = get_sanitized_event_args();

$result = create_event($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="_handleCreateInquiryError(\'' . $message . '\')"></span>';
    exit;
}

$is_inquiry = isset( $_POST['is_inquiry'] ) ? rest_sanitize_boolean( $_POST['is_inquiry'] ) : false;

// Success Response
if ($is_inquiry) {
    echo '<span x-init="_handleCreateInquirySuccess(\'' . $result['permalink'] . '\')"></span>';
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Event Created Successfully' . '\'})"></span>';
    echo '<span x-init="$dispatch(\'add-event\', {\'post_id\': \'' . $result['post_id'] . '\', \'event_name\': \'' . $result['event_name'] . '\', \'listings\': ' . clean_arr_for_doublequotes($result['listings']) . ', \'permalink\': \'' . $result['permalink'] . '\' })"></span>';
} else {
    echo '<span x-init="redirect(\'' . $result['permalink'] . '?toast=create\');"></span>'; exit;
}
