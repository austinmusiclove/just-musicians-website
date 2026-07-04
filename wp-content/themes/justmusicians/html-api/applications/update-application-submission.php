<?php

$submission_id = (int) get_query_var('app-submission');

$args = get_sanitized_application_submission_args();
$args['ID'] = $submission_id;

$result = update_user_application_submission($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

$message = get_post_meta($submission_id, 'message', true);
$status  = get_post_meta($submission_id, 'status', true);
$updated = get_the_modified_time('F j, Y', $submission_id);
?>

<span x-init="$dispatch('success-toast', { 'message': 'Application Updated Successfully'})"></span>
<span x-init="$dispatch('update-submission', {
    'message': '<?php echo clean_str_for_doublequotes($message ?? ''); ?>',
    'status':  '<?php echo clean_str_for_doublequotes($status  ?? ''); ?>',
    'updated': '<?php echo clean_str_for_doublequotes($updated ?? ''); ?>',
})"></span>
