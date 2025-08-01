<?php

// Check if user is authorized

$args = get_post_account_settings_args();
$result = update_account_settings($args);
if ( is_wp_error($result) ) {
    $message = $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
} else {
    if (isset($result['profile_image_attachment_id'])) {
        echo '<span x-init="$dispatch(\'updateimageid\', ' . clean_arr_for_doublequotes($result['profile_image_attachment_id']) . ')"></span>';
    }
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Account settings updated successfully\'})"></span>'; exit;
}
