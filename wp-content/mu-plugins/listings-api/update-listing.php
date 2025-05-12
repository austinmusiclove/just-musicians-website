<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _update_listing($args) {

    if (empty($args['ID'])) {
        return new WP_Error(400, 'Cannot update listing with out post id');
    }

    // ensemble_size
    // venues_played_verified
    // venues_played_unverified_strings
    // draw
    // email
    // phone
    // website
    // instagram_handle
    // instagram_url
    // instagram is private
    // tiktok_handle
    // tiktok_url
    // x_handle
    // x_url
    // facebook_url
    // youtube_url
    // bandcamp_url
    // spotify_artist_url
    // spotify_artist_id
    // apple_music_artist_url
    // soundcloud_url
    // youtube_video_urls
    // unofficial_tags
    // add status complete or incomplete


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

















