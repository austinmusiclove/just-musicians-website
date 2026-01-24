<?php

// Check if user is authorized

// Check Args
$args = get_sanitized_compensation_report_args();
if ( is_wp_error($args) ) {
    status_header(400);
    $message = $args->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
}

// Create
$result = create_compensation_report($args);
if ( is_wp_error($result) ) {
    status_header(500);
    $message = $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
} else {
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Your Compensation Report was submitted successfully\'})"></span>'; exit;
}
