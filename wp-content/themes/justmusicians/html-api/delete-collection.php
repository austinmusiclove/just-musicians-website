<?php

$collection_id = get_query_var('collection-id');
$args = ['collection_id' => $collection_id];


// Check if user is authorized
$is_authorized = user_owns_collection($args);
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'delete-error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Delete Collection
$delete_result = delete_collection($collection_id);
if ( is_wp_error($delete_result) ) {
    $message = 'Error: ' . $delete_result->get_error_message();
    echo '<span x-init="$dispatch(\'delete-error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'delete-success-toast\', { \'message\': \'' . 'Collection Deleted Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'hide-collection\')"></span>';

