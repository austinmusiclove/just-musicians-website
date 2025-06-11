<?php

// Get sanitized args from request
$args = get_sanitized_listing_args();

// Check if user is authorized
$is_authorized = check_post_listing_auth();
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
}


// Create Listing Draft
if ( empty($args['ID']) and $args['post_status'] == 'draft' ) {
    $post_id = _create_listing($args);
    if ( is_wp_error($post_id) ) {
        $message = 'Error: ' . $post_id->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="redirect(\'/listing-form/?lid=' . $post_id . '&toast=create-draft\');"></span>'; exit;
}


// Create Published Listing
if ( empty($args['ID']) and $args['post_status'] == 'publish' ) {
    $post_id = _create_listing($args);
    if ( is_wp_error($post_id) ) {
        $message = 'Error: ' . $post_id->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="redirect(\'/listing-form/?lid=' . $post_id . '&toast=create\');"></span>'; exit;
}


// Update Listing Draft
if ( !empty($args['ID']) and get_post_status($args['ID']) == 'draft' and $args['post_status'] == 'draft' ) {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        $message = 'Error: ' . $result->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Listing Draft Updated Successfully\'})"></span>';
}


// Publish Listing Draft
if ( !empty($args['ID']) and get_post_status($args['ID']) == 'draft' and $args['post_status'] == 'publish' ) {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        $message = 'Error: ' . $result->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="redirect(\'/listing-form/?lid=' . $args['ID'] . '&toast=publish-draft\');"></span>';
}


// Update Published Listing
if ( !empty($args['ID']) and get_post_status($args['ID']) == 'publish' and $args['post_status'] == 'publish' ) {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        $message = 'Error: ' . $result->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Listing Updated Successfully\'})"></span>';
}
