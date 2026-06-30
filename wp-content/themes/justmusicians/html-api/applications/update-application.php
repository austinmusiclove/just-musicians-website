<?php

$args = get_sanitized_application_args();
$args['ID'] = get_query_var('application-id');

$result = update_user_application($args);

if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Application Updated Successfully\' })"></span>';
echo '<span x-init="$dispatch(\'update-application\', { \'application\': ' . clean_arr_for_doublequotes($result) . ' })"></span>';
