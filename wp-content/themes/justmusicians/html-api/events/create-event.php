<?php

$args       = get_sanitized_event_args();
$is_inquiry = isset( $_POST['is_inquiry'] ) ? rest_sanitize_boolean( $_POST['is_inquiry'] ) : false;

// Create Event
$result = create_event($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    if ($is_inquiry) {
        echo '<span x-init="_handleCreateInquiryError(\'' . $message . '\')"></span>'; exit;
    } else {
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
}

// Success Response
if ($is_inquiry) {
    echo '<span x-init="_handleCreateInquirySuccess(\'' . $result['permalink'] . '\')"></span>';
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Event Created Successfully' . '\'})"></span>';
    echo '<span x-init="$dispatch(\'add-event\', {\'post_id\': \'' . $result['post_id'] . '\', \'event_name\': \'' . $result['event_name'] . '\', \'listings\': ' . clean_arr_for_doublequotes($result['listings']) . ', \'permalink\': \'' . $result['permalink'] . '\' })"></span>';
    echo '<span x-init="accountSettings = ' . clean_arr_for_doublequotes(get_user_account_settings(get_current_user_id())) . '"></span>';
} else {
    echo '<span x-init="redirect(\'' . $result['permalink'] . '?toast=create\');"></span>'; exit;
}
