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
$result = remove_listing_from_collection($collection_id, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
$listing_count = count($result->data['listings']);
$listing_count_descriptor = $listing_count == 1 ? ' Listing' : ' Listings';
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Listing Removed Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'hide-listing\')"></span>';
echo '<span id="max_num_results" hx-swap-oob="outerHTML">' . $listing_count . $listing_count_descriptor . '</span>';
