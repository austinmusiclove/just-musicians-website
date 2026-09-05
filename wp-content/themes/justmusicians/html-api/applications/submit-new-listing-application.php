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
// if user is logged out and this is not an embedded form, try to get them to sign up by redirecting them to the sign up link
} else if (isset($result['sign_up_link']) && empty($_POST['embed'])) { ?>
    <span x-init="redirect('<?php echo $result['sign_up_link']; ?>');"></span>
    <?php exit;
}
?>

<span x-init="$dispatch('success-toast', { 'message': 'Application Submitted Successfully'})"></span>
<span x-init="$dispatch('hideform');"></span>
<?php get_template_part('template-parts/applications/musician-application/successful-submission'); ?>
