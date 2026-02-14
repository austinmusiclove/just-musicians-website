<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_inquiry($args) {
    $post_id = $args['post_id'];

    // Check if the post ID is valid
    if (empty($post_id) || !is_numeric($post_id)) {
        return new WP_Error('invalid_post_id', 'Invalid post ID.');
    }

    // Retrieve the post object to check if the post exists
    global $post;
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('post_not_found', 'Post not found.');
    }

    // Check if the post is of type 'inquiry'
    if ($post->post_type !== 'inquiry') {
        return new WP_Error('invalid_post_type', 'The post is not of type "inquiry".');
    }

    // Formatted time
    $time = get_field('time', false, false);            // raw value (military)
    $formatted_time = date("g:i A", strtotime($time));  // convert to 12-hour

    // Array to store post meta and taxonomy data
    $result = [
        'inquiry_id'      => $post_id,
        'subject'         => get_field('subject'),
        'details'         => nl2br(get_field('details')),
        'date'            => get_field('date'),
        'date_type'       => get_field('date_type'),
        'zip_code'        => get_field('zip_code'),
        'time'            => $formatted_time,
        'budget_type'     => get_field('budget_type'),
        'budget'          => get_field('budget'),
        'percent_of_door' => get_field('percent_of_door'),
        'percent_of_bar'  => get_field('percent_of_bar'),
        'expired'         => get_field('expired'),
    ];


    // Return the complete data
    wp_reset_postdata();
    return $result;
}
