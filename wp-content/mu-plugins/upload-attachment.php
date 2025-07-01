<?php
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );

function upload_attachment($file, $filename, $post_id, $caption, $mediatags) {
    // Upload
    $attachment_upload = wp_handle_upload($file, ['test_form' => false]);
    if (!isset($attachment_upload['file'])) {
        return new WP_Error(500, 'Failed to upload image');
    }

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $attachment_upload['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_excerpt'   => $caption,
    );

    // Create the attachment
    $attachment_id = wp_insert_attachment( $attachment, $attachment_upload['file'], $post_id );
    if( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    // Set attachment meta data
    $attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $attachment_upload['file'] );
    wp_update_attachment_metadata( $attachment_id, $attachment_metadata );

    // Update media tags
    update_attachment_mediatags($attachment_id, $mediatags);

    return $attachment_id;
}

function update_attachment_mediatags($attachment_id, $mediatags) {
    if (is_array($mediatags)) {
        wp_set_object_terms($attachment_id, $mediatags, 'mediatag');
    }
}

function update_attachment_caption($attachment_id, $caption) {
    $attachmet_id = wp_update_post([ 'ID' => $attachment_id, 'post_excerpt' => $caption, ], true);
    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }
}
