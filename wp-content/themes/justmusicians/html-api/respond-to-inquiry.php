<?php

$proposal_id = (int) get_query_var('proposal-id');
$args = [
    'proposal_id' => $proposal_id,
];

if (isset($_POST['details']))      { $args['details']      = sanitize_textarea_field($_POST['details']); }
if (isset($_POST['availability'])) { $args['availability'] = sanitize_text_field($_POST['availability']); }
if (isset($_POST['quote']))        { $args['quote']        = sanitize_text_field($_POST['quote']); }
if (isset($_POST['draw']))         { $args['draw']         = sanitize_text_field($_POST['draw']); }

$result = respond_to_inquiry_proposal($args);
if (is_wp_error($result)) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

$response_details      = $args['details']      ?? get_post_meta($proposal_id, 'details', true);
$response_availability = $args['availability'] ?? get_post_meta($proposal_id, 'availability', true);
$response_quote        = $args['quote']        ?? get_post_meta($proposal_id, 'quote', true);
$response_draw         = $args['draw']         ?? get_post_meta($proposal_id, 'draw', true);

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Response Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-proposal\', { \'details\': \'' . $response_details . '\', \'availability\': \'' . $response_availability . '\', \'quote\': \'' . $response_quote . '\', \'draw\': \'' . $response_draw . '\'})"></span>';
