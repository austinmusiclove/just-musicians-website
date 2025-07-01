<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _update_listing($args) {

    if (empty($args['ID'])) {
        return new WP_Error(400, 'Cannot update listing without post id');
    }

    $attachment_ids = [
        'cover_image'    => '',
        'listing_images' => [],
        'stage_plots'    => [],
    ];
    $listing_images_ids = [];
    $stage_plots_ids    = [];
    $youtube_video_ids  = [];

    // cover image
    if (!empty($args['cover_image']) and is_array($args['cover_image'])) {
        if (!empty($args['cover_image']['file']) and empty($args['cover_image']['attachment_id'])) {
            $attachment_id = upload_attachment($args['cover_image']['file'], $args['cover_image']['filename'], $args['ID'], '', $args['cover_image']['mediatags']);
            if ( is_wp_error( $attachment_id ) ) {
                return $attachment_id;
            }
            set_post_thumbnail($args['ID'], $attachment_id);
            $attachment_ids['cover_image'] = $attachment_id;
        } else if (!empty($args['cover_image']['attachment_id'])) {
            if (array_key_exists('mediatags', $args['cover_image'])) { update_attachment_mediatags($args['cover_image']['attachment_id'], $args['cover_image']['mediatags']); }
        }
    }
    // listing images
    if (!empty($args['listing_images']) and is_array($args['listing_images'])) {
        foreach ($args['listing_images'] as $image_data) {
            if (isset($image_data['file']) and empty($image_data['attachment_id'])) {
                $attachment_id = upload_attachment($image_data['file'], $image_data['filename'], $args['ID'], '', $image_data['mediatags']);
                if ( is_wp_error( $attachment_id ) ) {
                    return $attachment_id;
                }
                $attachment_ids['listing_images'][$image_data['image_id']] = $attachment_id;
                $listing_images_ids[] = $attachment_id;
            } else if (!empty($image_data['attachment_id'])) {
                if (array_key_exists('mediatags', $image_data)) { update_attachment_mediatags($image_data['attachment_id'], $image_data['mediatags']); }
                $attachment_ids['listing_images'][$image_data['image_id']] = $image_data['attachment_id'];
                $listing_images_ids[] = $image_data['attachment_id'];
            }
        }
    }
    // stage plots
    if (!empty($args['stage_plots']) and is_array($args['stage_plots'])) {
        foreach ($args['stage_plots'] as $image_data) {
            if (isset($image_data['file']) and empty($image_data['attachment_id'])) {
                $attachment_id = upload_attachment($image_data['file'], $image_data['filename'], $args['ID'], $image_data['caption'], $image_data['mediatags']);
                if ( is_wp_error( $attachment_id ) ) {
                    return $attachment_id;
                }
                $attachment_ids['stage_plots'][$image_data['image_id']] = $attachment_id;
                $stage_plots_ids[] = $attachment_id;
            } else if (!empty($image_data['attachment_id'])) {
                if (array_key_exists('caption',   $image_data)) { update_attachment_caption(  $image_data['attachment_id'], $image_data['caption']); }
                if (array_key_exists('mediatags', $image_data)) { update_attachment_mediatags($image_data['attachment_id'], $image_data['mediatags']); }
                $attachment_ids['stage_plots'][$image_data['image_id']] = $image_data['attachment_id'];
                $stage_plots_ids[] = $image_data['attachment_id'];
            }
        }
    }
    // youtube videos
    if (!empty($args['youtube_videos']) and is_array($args['youtube_videos'])) {
        foreach ($args['youtube_videos'] as $video_data) {
            if (empty($video_data['post_id'])) {
                $video_post_id = insert_youtube_video($video_data , true);
                if ( is_wp_error( $video_post_id ) ) {
                    return $video_post_id;
                }
                $attachment_ids['youtube_videos'][$video_data['video_id']] = $video_post_id;
                $youtube_video_ids[] = $video_post_id;
            } else {
                if (array_key_exists('mediatags', $video_data))  { update_attachment_mediatags($video_data['post_id'], $video_data['mediatags']); }
                if (array_key_exists('start_time', $video_data)) { update_post_meta($video_data['post_id'], 'start_time', $video_data['start_time']); }
                $attachment_ids['youtube_videos'][$video_data['video_id']] = $video_data['post_id'];
                $youtube_video_ids[] = $video_data['post_id'];
            }
        }
    }
    $args['meta_input']['listing_images'] = $listing_images_ids;
    $args['meta_input']['stage_plots']    = $stage_plots_ids;
    $args['meta_input']['youtube_videos'] = $youtube_video_ids;

    // Update post; this returns post_id on success and WP_Error on failure
    $post_id = wp_update_post($args, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    return [
        'post_id'        => $post_id,
        'attachment_ids' => $attachment_ids,
    ];
}
