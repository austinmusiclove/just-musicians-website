<?php

$collection_id = get_query_var('collection-id');
$listing_id    = get_query_var('listing-id');

// Check if user is authorized
$is_authorized = user_can_edit_collection($collection_id);
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
$collection_name = $collection_id > 0 ? get_post_meta($collection_id, 'name', true) : 'Favorites';
?>
<span x-init="$dispatch('success-toast', { 'message': 'Listing added to <?php echo $collection_name; ?>' })"></span>
<span x-init="$dispatch('add-listing-to-collection', { 'collection_id': '<?php echo $collection_id; ?>', 'listing_id': '<?php echo $listing_id; ?>' })"></span>
<span x-init="showCollectionsPopup = true;"></span>
