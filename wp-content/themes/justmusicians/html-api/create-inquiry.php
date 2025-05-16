<?php


// Create Inquiry
$args = get_sanitized_inquiry_args();

$result = create_user_inquiry($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="_handleCreateInquiryError(\'' . $message . '\')"></span>';
    exit;
}

// Success Response
echo '<span x-init="_handleCreateInquirySuccess()"></span>';
