<?php
function send_failed_to_generate_tmp_code_email($context, $err) {
    $application_title = get_post_meta($application_id, 'title', true);
    $subject = 'Failed to generate tmp_code';
    $message = 'There was an error generating a tmp_code for ' . $context . ".\n\n" . 'Error: ' . $err->get_error_message();
    send_email_to_hm_admin($subject, $message);
}
