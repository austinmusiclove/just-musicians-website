<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _create_listing($args) {

    // Insert post; this returns post_id on success and WP_Error on failure
    $post_id = wp_insert_post($args, true);
    if( is_wp_error( $post_id ) ) { return $post_id; exit; }

    // Thumbnail Image
    if (!empty($args['_thumbnail_file'])) {

        // Upload
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $thumbnail_upload = wp_handle_upload($args['_thumbnail_file'], ['test_form' => false]);
        if (!isset($thumbnail_upload['file'])) {
            return new WP_Error(500, 'Failed to upload image');
        }

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $thumbnail_upload['type'],
            'post_title'     => sanitize_file_name( $thumbnail_upload['file'] ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Create the attachment
        $attachment_id = wp_insert_attachment( $attachment, $thumbnail_upload['file'], $post_id );
        if( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Set attachment meta data
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $thumbnail_upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $attachment_metadata );

        // Set post thumbnail
        set_post_thumbnail($post_id, $attachment_id);
    }

    // Add post to user listings
    add_listing_to_current_user($post_id);

    return $post_id;
}

function add_listing_to_current_user($post_id) {
    // Get current user ID
    $user_id = get_current_user_id();

    // Get current listings for the user
    $listings = get_user_meta($user_id, 'listings', true);
    if (!is_array($listings)) { $listings = []; }

    // Avoid duplicates
    if (!in_array($post_id, $listings)) {
        $listings[] = $post_id;
        update_user_meta($user_id, 'listings', $listings);
    }
}
