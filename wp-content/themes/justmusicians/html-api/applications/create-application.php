<?php

$args = get_sanitized_application_args();


$result = create_application($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

echo '<span x-init="redirect(\'' . $result['permalink'] . '?toast=create\');"></span>'; exit;
