<?php

$collection_id = get_query_var('collection-id');


// Check if user is authorized
$is_authorized = user_can_edit_collection($collection_id);
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message(); ?>
    <span x-init="$dispatch('error-toast', { 'message': '<?php echo $message; ?>'})"></span>';
    <?php exit;
}

// Parse ordered listing ids in DOM order from the reorderable card inputs
$ordered_ids = [];
if (!empty($_POST['reorder_ids'])) {
    $ordered_ids = array_filter(array_map('trim', (array) $_POST['reorder_ids']), 'is_numeric');
}

// Reorder collection listings
$result = reorder_collection_listings($collection_id, $ordered_ids, 0);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message(); ?>
    <span x-init="$dispatch('error-toast', { 'message': '<?php echo $message; ?>'})"></span>';
    <?php exit;
}

// Success Response ?>
<span x-init="$dispatch('success-toast', { 'message': 'Collection Order Saved'})"></span>
