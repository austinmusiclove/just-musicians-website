<?php

// Get sanitized args from request
$args = get_sanitized_listing_args();


// Check if user is authorized
$is_authorized = check_post_listing_auth();
if ( is_wp_error($is_authorized) ) {
    echo get_template_part('template-parts/global/toasts/error-toast', '', [
        'message' => 'Unauthorized: ' . $is_authorized->get_error_message(),
    ]);
    echo  exit;
}


// Create Listing
if ( empty( $args['ID'] )) {
    $post_id = _create_listing($args);
    if ( is_wp_error($post_id) ) {
        echo get_template_part('template-parts/global/toasts/error-toast', '', [
            'message' => 'Error: ' . $post_id->get_error_message(),
        ]);
        exit;
    }
    echo '<span x-init="redirect(\'/listing-form-dev/?lid=' . $post_id . '&toast=create\');"></span>';



// Update Listing
} else {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        echo get_template_part('template-parts/global/toasts/error-toast', '', [
            'message' => 'Error: ' . $result->get_error_message(),
        ]);
        exit;
    }
    echo get_template_part('template-parts/global/toasts/success-toast', '', [
        'message' => 'Listing Updated Successfully'
    ]);
}

