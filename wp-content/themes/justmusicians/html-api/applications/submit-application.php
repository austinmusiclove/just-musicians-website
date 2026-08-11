<?php

$args = get_sanitized_application_submission_args();
$args['meta_input']['application'] = (int) get_query_var('application-id');
$args['meta_input']['listing']     = (int) get_query_var('listing-id');

$result = submit_application($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}
?>

<span x-init="$dispatch('success-toast', { 'message': 'Application Submitted Successfully'})"></span>
<span x-init="$dispatch('hideform');"></span>
<?php get_template_part('template-parts/applications/musician-application/successful-submission'); ?>

