<?php

$collection_id = get_query_var('collection-id');
$listing_id    = get_query_var('listing-id');
$args = ['collection_id' => $collection_id];


// Check if user is authorized
$is_authorized = user_owns_collection($args);
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Add listing to collection
$result = add_listing_to_collection($collection_id, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Listing Added Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'add-listing-to-collection\', {\'collection_id\': \'' . $collection_id . '\', \'listing_id\': \'' . $listing_id . '\' })"></span>';
echo '<span x-init="showCollectionsPopup = true;"></span>';
