<?php

// Check if user is authorized

$args = get_sanitized_compensation_report_args();
$result = create_compensation_report($args);
if ( is_wp_error($result) ) {
    $message = $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
} else {
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Your Compensation Report was submitted successfully\'})"></span>'; exit;
}
