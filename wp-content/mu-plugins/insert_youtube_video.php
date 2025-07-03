<?php

function insert_youtube_video($video_data) {

    // Set post data
    $args = [
        'post_type'   => 'youtubevideo',
        'post_status' => 'publish',
        'post_title'  => $video_data['url'],
        'meta_input'  => [
            'url'         => $video_data['url'],
            'video_id'    => $video_data['video_id'],
            'start_time'  => $video_data['start_time'],
        ],
    ];

    error_log(print_r($args, true));
    // Create the post
    $video_post_id = wp_insert_post($args, true);
    if( is_wp_error( $video_post_id ) ) {
        return $video_post_id;
    }

    // Update media tags
    update_attachment_mediatags($video_post_id, $video_data['mediatags']);

    return $video_post_id;
}
