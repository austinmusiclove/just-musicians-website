<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _update_listing($args) {

    if (empty($args['ID'])) {
        return new WP_Error(400, 'Cannot update listing with out post id');
    }

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
        $attachment_id = wp_insert_attachment( $attachment, $thumbnail_upload['file'], $args['ID'] );
        if( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Set attachment meta data
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $thumbnail_upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $attachment_metadata );

        // Set post thumbnail
        set_post_thumbnail($args['ID'], $attachment_id);
    }


    // Update post; this returns post_id on success and WP_Error on failure
    return wp_update_post($args, true);
}

















