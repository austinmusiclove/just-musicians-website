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

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Response Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-proposal\', {';
echo "    'status':           '" . clean_str_for_doublequotes($status) .           "',";
echo "    'details':          '" . clean_str_for_doublequotes($details) .          "',";
echo "    'availability':     '" . clean_str_for_doublequotes($availability) .     "',";
echo "    'quote':            '" . clean_str_for_doublequotes($quote) .            "',";
echo "    'draw':             '" . clean_str_for_doublequotes($draw) .             "',";
echo "    'proposal_updated': '" . clean_str_for_doublequotes($proposal_updated) . "'";
echo '})"></span>';
echo '<span x-init="notifications = await get_user_notifications();"></span>';
