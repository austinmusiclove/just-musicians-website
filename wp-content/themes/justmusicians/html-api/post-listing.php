<?php

// Get sanitized args from request
$args = get_sanitized_listing_args();


// Check if user is authorized
$is_authorized = check_post_listing_auth();
if ( is_wp_error($is_authorized) ) {
    echo 'Unauthorized: ' . $is_authorized->get_error_message(); exit;
}


// Create Listing
if ( empty( $args['ID'] )) {
    $post_id = _create_listing($args);
    if ( is_wp_error($post_id) ) { echo 'Error: ' . $post_id->get_error_message(); exit; }
    echo '<span x-init="redirect(\'/listing-form-dev/?lid=' . $post_id . '\');"></span>';
    echo 'Listing Created Successfully';


// Update Listing
} else {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) { echo 'Error: ' . $result->get_error_message(); exit; }
    echo 'Listing Updated Successfully';
}

