<?php

// Get sanitized args from request
$args = get_sanitized_listing_args();
if ( is_wp_error($args) ) {
    $message = $args->get_error_message();
    $code = $args->get_error_code();
    switch ($code) {
        case 'missing_cover_image':
            $message = 'Cover Image Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'cover-image\'})"></span>';
            break;
        case 'missing_state':
            $message = 'State Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'state\'})"></span>';
            break;
        case 'invalid_email':
            $message = 'Email Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'listing_email\'})"></span>';
            break;
        case 'invalid_phone':
            $message = 'Phone Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'phone\'})"></span>';
            break;
        case 'invalid_website':
            $message = 'Website Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'website\'})"></span>';
            break;
        case 'invalid_facebook_url':
            $message = 'Facebook Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'facebook_url\'})"></span>';
            break;
        case 'invalid_instagram_url':
            $message = 'Instagram Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'instagram_url\'})"></span>';
            break;
        case 'invalid_tiktok_url':
            $message = 'Tiktok Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'tiktok_url\'})"></span>';
            break;
        case 'invalid_x_url':
            $message = 'X Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'x_url\'})"></span>';
            break;
        case 'invalid_youtube_url':
            $message = 'Youtube Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'youtube_url\'})"></span>';
            break;
        case 'invalid_bandcamp_url':
            $message = 'Bandcamp Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'bandcamp_url\'})"></span>';
            break;
        case 'invalid_apple_music_artist_url':
            $message = 'Apple Music Artist Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'apple_music_artist_url\'})"></span>';
            break;
        case 'invalid_soundcloud_url':
            $message = 'Soundcloud Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'soundcloud_url\'})"></span>';
            break;
        case 'invalid_spotify_artist_url':
            $message = 'Spotify Artist Url Error: ' . $message;
            echo '<span x-init="$dispatch(\'focus-elm\', { \'id\': \'spotify_artist_url\'})"></span>';
            break;
    }
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>'; exit;
}


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
