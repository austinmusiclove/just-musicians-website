<?php

// Get sanitized args from request
$args = get_sanitized_listing_args();

// Check if user is authorized
$is_authorized = check_post_listing_auth();
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
}

// Check for required fields
if (empty($args['cover_image']['attachment_id']) and empty($args['cover_image']['file'])) {
    $message = 'Error: Cover image required';
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
}
if (empty($args['meta_input']['state'])) {
    $message = 'Error: State required';
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
    echo '<span x-init="$dispatch(\'updateimageids\', ' . clean_arr_for_doublequotes($result['attachment_ids']) . ')"></span>';
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Listing Draft Updated Successfully\'})"></span>'; exit;
}


// Publish Listing Draft
if ( !empty($args['ID']) and get_post_status($args['ID']) == 'draft' and $args['post_status'] == 'publish' ) {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        $message = 'Error: ' . $result->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="redirect(\'/listing-form/?lid=' . $args['ID'] . '&toast=publish-draft\');"></span>'; exit;
}


// Update Published Listing
if ( !empty($args['ID']) and get_post_status($args['ID']) == 'publish' and $args['post_status'] == 'publish' ) {
    $result = _update_listing($args);
    if ( is_wp_error($result) ) {
        $message = 'Error: ' . $result->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
    }
    echo '<span x-init="$dispatch(\'updateimageids\', ' . clean_arr_for_doublequotes($result['attachment_ids']) . ')"></span>';
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Listing Updated Successfully\'})"></span>'; exit;
}
