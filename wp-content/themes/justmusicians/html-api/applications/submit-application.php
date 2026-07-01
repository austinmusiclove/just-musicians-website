<?php

$args = get_sanitized_application_submission_args();
$args['meta_input']['application'] = (int) get_query_var('application-id');

$result = submit_application($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Application Submitted Successfully\'})"></span>';
