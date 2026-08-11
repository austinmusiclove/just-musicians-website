<?php

$submission_args = get_sanitized_application_submission_args();
$submission_args['meta_input']['application'] = (int) get_query_var('application-id');

$listing_args = get_sanitized_listing_args();
if (is_wp_error($listing_args)) {
    $message = 'Error: ' . $listing_args->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

$result = submit_new_listing_application($submission_args, $listing_args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
} else if (isset($result['sign_up_link'])) {
    wp_safe_redirect($result['sign_up_link']);
    exit;
}
?>

<span x-init="$dispatch('success-toast', { 'message': 'Application Submitted Successfully'})"></span>
<span x-init="$dispatch('hideform');"></span>
<?php get_template_part('template-parts/applications/musician-application/successful-submission-new-listing'); ?>

