<?php

// Ensure the user is logged in
if (!is_user_logged_in()) {
    $message = 'Unauthorized: Must be logged in to create a collection';
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

$collection_name = !empty($_POST['collection_name']) ? $_POST['collection_name'] : '';
$listing_id      = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';


// Add listing to collection
$result = create_user_collection($collection_name, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Collection Created Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'add-collection\', {\'post_id\': \'' . $result['post_id'] . '\', \'name\': \'' . $result['name'] . '\', \'listings\': ' . clean_arr_for_doublequotes($result['listings']) . ', \'permalink\': \'' . $result['permalink'] . '\' })"></span>';
echo '<span x-init="$refs.newCollectionInput' . $listing_id . '.value = \'\';"></span>';
