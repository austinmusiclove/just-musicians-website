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

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Response Updated Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'update-proposal\', { \'details\': \'' . $args['details'] . '\', \'availability\': \'' . $args['availability'] . '\', \'quote\': \'' . $args['quote'] . '\', \'draw\': \'' . $args['draw'] . '\'})"></span>';
