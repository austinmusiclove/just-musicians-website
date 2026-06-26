<?php

// Create Inquiry
$args = get_sanitized_inquiry_args(false);

$result = create_user_inquiry($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="_handleCreateInquiryError(\'' . $message . '\')"></span>';
    exit;
}

// Success Response
echo '<span x-init="_handleCreateInquirySuccess(\'' . $result['post_id'] . '\')"></span>';
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Inquiry Created Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'add-inquiry\', {\'post_id\': \'' . $result['post_id'] . '\', \'subject\': \'' . $result['subject'] . '\', \'listings\': ' . clean_arr_for_doublequotes($result['listings']) . ', \'permalink\': \'' . $result['permalink'] . '\' })"></span>';
