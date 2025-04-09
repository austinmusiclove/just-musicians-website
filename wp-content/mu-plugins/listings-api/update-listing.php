<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function update_listing($args) {

    // err if no id

    $listing_post = array(
        'ID'           => sanitize_text_field($args['post_id']),
        'post_status'  => 'publish',
        'post_type'    => 'listing',
        'meta_input'   => [],
        'tax_input'    => [],
    );
    // Name
    if (!empty($args['name'])) {
        $name = sanitize_text_field($args['name']);
        $listing_post['post_title'] = $name;
        $listing_post['meta_input']['name'] = $name;
    }

    // Meta Input
    if (!empty($args['description'])) { $listing_post['meta_input']['description'] = sanitize_text_field($args['description']); }
    if (!empty($args['city']))        { $listing_post['meta_input']['city']        = sanitize_text_field($args['city']); }
    if (!empty($args['state']))       { $listing_post['meta_input']['state']       = sanitize_text_field($args['state']); }
    if (!empty($args['zip_code']))    { $listing_post['meta_input']['zip_code']    = sanitize_text_field($args['zip_code']); }
    if (!empty($args['bio']))         { $listing_post['meta_input']['bio']         = sanitize_textarea_field($args['bio']); }
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

    // Taxonomy Input
    //if (!empty($args['genres'])) { $listing_post['tax_input']['genre'] = $args['genres']; }
    wp_set_post_terms($args['post_id'], $args['categories'], 'mcategory');
    wp_set_post_terms($args['post_id'], $args['genres'], 'genre');
    wp_set_post_terms($args['post_id'], $args['subgenres'], 'subgenre');
    wp_set_post_terms($args['post_id'], $args['instrumentations'], 'instrumentation');
    wp_set_post_terms($args['post_id'], $args['settings'], 'setting');

    // Add featured image and don't show error if thumbnail fails
    if (!empty($args['thumbnail_file'])) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $thumbnail_upload = wp_handle_upload($args['thumbnail_file']);
        if (isset($thumbnail_upload['file'])) {
            // Set attachment data
            $attachment = array(
                'post_mime_type' => $thumbnail_upload['type'],
                'post_title'     => sanitize_file_name( $thumbnail_upload['file'] ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Create the attachment
            $attachment_id = wp_insert_attachment( $attachment, $thumbnail_upload['file'], $args['post_id'] );
            if( !is_wp_error( $attachment_id ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $thumbnail_upload['file'] );
                wp_update_attachment_metadata( $attachment_id, $attachment_metadata );
                set_post_thumbnail($args['post_id'], $attachment_id);
            }
        }
    }
    echo '<h2>Thank you for your submission!</h2>';
    echo '<p>Your listing has been submitted successfully.</p>';


    $result = wp_update_post($listing_post, true);
    if (is_wp_error($result)) {
        return $result->get_error_message();
    }
    return $result;
}

