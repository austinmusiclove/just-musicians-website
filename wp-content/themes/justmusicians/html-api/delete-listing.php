<?php

$post_id = get_query_var('listing-id');
$args = ['post_id' => $post_id];


// Check if user is authorized
$is_authorized = check_delete_listing_auth($args);
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'delete-error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}


// Delete Listing
$post = _delete_listing($args);
if ( is_wp_error($post) ) {
    $message = 'Error: ' . $post->get_error_message();
    echo '<span x-init="$dispatch(\'delete-error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="$dispatch(\'delete-success-toast\', { \'message\': \'' . 'Listing Deleted Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'hide-listing\')"></span>';
