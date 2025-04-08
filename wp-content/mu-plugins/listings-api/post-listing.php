<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function post_listing($args) {
    // get valid args
    $name = sanitize_text_field($args['name']);

    // err if no id
    // put valid args into correct input
    // update post
    // add status complete or incomplete

    $listing_post = array(
        'post_title'   => $name,
        'post_status'  => 'publish',
        'post_type'    => 'listing',
        'meta_input'   => array(
            'name' => $name,
        ),
    );


    $result = wp_insert_post($listing_post, true);
    if (is_wp_error($result)) {
        return $result->get_error_message();
    }
    return;
}
