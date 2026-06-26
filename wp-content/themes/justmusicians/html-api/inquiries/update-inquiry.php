<?php

// Update Inquiry
$args = get_sanitized_inquiry_args(true);
$args['ID'] = get_query_var('inquiry-id');

$result = update_user_inquiry($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Inquiry Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-inquiry\', {
    \'post_id\': \'' . $result['post_id'] . '\',
    \'inquiry\': {
        \'subject\': \'' . $result['subject'] . '\',
        \'details\': \'' . clean_str_for_doublequotes(nl2br($result['details'])) . '\',
        \'raw_details\': \'' . clean_str_for_doublequotes($result['details']) . '\',
    }
})"></span>';
