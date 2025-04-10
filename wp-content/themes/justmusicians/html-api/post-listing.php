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
    $result = _create_listing($args);
    if ( is_wp_error($result) ) { echo 'Error: ' . $result->get_error_message(); exit; }
    echo 'Listing Created Successfully';


// Update Listing
} else {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) { echo 'Error: ' . $result->get_error_message(); exit; }
    echo 'Listing Updated Successfully';
}

