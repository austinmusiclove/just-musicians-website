<?php

$proposal_id = (int) get_query_var('proposal-id');
$args        = get_sanitized_proposal_args();
$args['ID']  = $proposal_id;

$result = respond_to_inquiry_proposal($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

$status           = get_post_meta($proposal_id, 'status', true);
$details          = get_post_meta($proposal_id, 'details', true);
$availability     = get_post_meta($proposal_id, 'availability', true);
$quote            = get_post_meta($proposal_id, 'quote', true);
$draw             = get_post_meta($proposal_id, 'draw', true);
$proposal_updated = get_the_modified_time('M j, Y', $proposal_id);
?>

<span x-init="$dispatch('success-toast', { 'message': 'Response Updated Successfully' })"></span>
<span x-init="$dispatch('update-proposal', {
    'status':           '<?php echo clean_str_for_doublequotes($status           ?? ''); ?>',
    'details':          '<?php echo clean_str_for_doublequotes($details          ?? ''); ?>',
    'availability':     '<?php echo clean_str_for_doublequotes($availability     ?? ''); ?>',
    'quote':            '<?php echo clean_str_for_doublequotes($quote            ?? ''); ?>',
    'draw':             '<?php echo clean_str_for_doublequotes($draw             ?? ''); ?>',
    'proposal_updated': '<?php echo clean_str_for_doublequotes($proposal_updated ?? ''); ?>',
})"></span>
<span x-init="notifications = await get_user_notifications();"></span>
