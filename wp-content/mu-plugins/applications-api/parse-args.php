<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_sanitized_application_args() {
    $sanitized_args = [
        'post_type'   => 'application',
        'post_status' => 'publish',
        'meta_input'  => [],
    ];

    if (isset($_POST['title']))       { $sanitized_args['post_title']                = sanitize_text_field($_POST['title']); }
    if (isset($_POST['title']))       { $sanitized_args['meta_input']['title']       = sanitize_text_field($_POST['title']); }
    if (isset($_POST['description'])) { $sanitized_args['meta_input']['description'] = sanitize_textarea_field($_POST['description']); }

    return $sanitized_args;
}
