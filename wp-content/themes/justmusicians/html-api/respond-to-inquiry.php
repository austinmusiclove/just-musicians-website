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

$status       = $args['status']       ?? get_post_meta($proposal_id, 'status', true);
$details      = $args['details']      ?? get_post_meta($proposal_id, 'details', true);
$availability = $args['availability'] ?? get_post_meta($proposal_id, 'availability', true);
$quote        = $args['quote']        ?? get_post_meta($proposal_id, 'quote', true);
$draw         = $args['draw']         ?? get_post_meta($proposal_id, 'draw', true);

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Response Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-proposal\', { \'status\': \'' . clean_str_for_doublequotes($status) . '\', \'details\': \'' . clean_str_for_doublequotes($details) . '\', \'availability\': \'' . clean_str_for_doublequotes($availability) . '\', \'quote\': \'' . clean_str_for_doublequotes($quote) . '\', \'draw\': \'' . clean_str_for_doublequotes($draw) . '\'})"></span>';
