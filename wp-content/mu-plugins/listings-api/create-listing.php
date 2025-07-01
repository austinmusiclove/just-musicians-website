<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _create_listing($args) {

    // Insert post; this returns post_id on success and WP_Error on failure
    $post_id = wp_insert_post($args, true);
    if( is_wp_error( $post_id ) ) {
        return $post_id;
    }

    // Add post to user listings
    add_listing_to_current_user($post_id);

    $attachment_ids = [
        'cover_image'    => '',
        'listing_images' => [],
        'stage_plots'    => [],
    ];
    $listing_images_ids = [];
    $stage_plots_ids    = [];
    $youtube_video_ids  = [];
    $update_args        = ['ID' => $post_id, 'meta_input' => []];

    // cover image
    if (!empty($args['cover_image']) and is_array($args['cover_image'])) {
        if (!empty($args['cover_image']['file']) and empty($args['cover_image']['attachment_id'])) {
            $attachment_id = upload_attachment($args['cover_image']['file'], $args['cover_image']['filename'], $post_id, '', $args['cover_image']['mediatags']);
            if ( is_wp_error( $attachment_id ) ) {
                return $attachment_id;
            }
            set_post_thumbnail($post_id, $attachment_id);
            $attachment_ids['cover_image'] = $attachment_id;
        }
    }
    // listing images
    if (!empty($args['listing_images']) and is_array($args['listing_images'])) {
        foreach ($args['listing_images'] as $image_data) {
            if (isset($image_data['file']) and empty($image_data['attachment_id'])) {
                $attachment_id = upload_attachment($image_data['file'], $image_data['filename'], $post_id, '', $image_data['mediatags']);
                if ( is_wp_error( $attachment_id ) ) {
                    return $attachment_id;
                }
                $attachment_ids['listing_images'][$image_data['image_id']] = $attachment_id;
                $listing_images_ids[] = $attachment_id;
            }
        }
    }
    // stage plots
    if (!empty($args['stage_plots']) and is_array($args['stage_plots'])) {
        foreach ($args['stage_plots'] as $image_data) {
            if (isset($image_data['file']) and empty($image_data['attachment_id'])) {
                $attachment_id = upload_attachment($image_data['file'], $image_data['filename'], $post_id, $image_data['caption'], $image_data['mediatags']);
                if ( is_wp_error( $attachment_id ) ) {
                    return $attachment_id;
                }
                $attachment_ids['stage_plots'][$image_data['image_id']] = $attachment_id;
                $stage_plots_ids[] = $attachment_id;
            }
        }
    }
    // youtube videos
    if (!empty($args['youtube_video_data']) and is_array($args['youtube_video_data'])) {
        foreach ($args['youtube_video_data'] as $video_data) {
            if (empty($video_data['post_id'])) {
                $video_post_id = insert_youtube_video($video_data , true);
                if ( is_wp_error( $video_post_id ) ) {
                    return $video_post_id;
                }
                $attachment_ids['youtube_videos'][$video_data['video_id']] = $video_post_id;
                $youtube_video_ids[] = $video_post_id;
            }
        }
    }

    $update_args['meta_input']['listing_images'] = $listing_images_ids;
    $update_args['meta_input']['stage_plots']    = $stage_plots_ids;
    $update_args['meta_input']['youtube_videos'] = $youtube_video_ids;
    $post_id = wp_update_post($update_args, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }
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
