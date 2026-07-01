<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_sanitized_application_args() {
    $sanitized_args = [
        'post_type'   => 'application',
        'post_status' => 'publish',
        'meta_input'  => [],
    ];

    if (isset($_POST['post_id']))     { $sanitized_args['ID']                        = sanitize_text_field($_POST['post_id']); }
    if (isset($_POST['title']))       { $sanitized_args['post_title']                = sanitize_text_field($_POST['title']); }
    if (isset($_POST['title']))       { $sanitized_args['meta_input']['title']       = sanitize_text_field($_POST['title']); }
    if (isset($_POST['description'])) { $sanitized_args['meta_input']['description'] = sanitize_textarea_field($_POST['description']); }

    return $sanitized_args;
}

function get_sanitized_application_submission_args() {
    $sanitized_args = [
        'post_type'   => 'app_submission',
        'post_status' => 'publish',
        'meta_input' => [],
    ];

    if (isset($_POST['application_id'])) { $sanitized_args['meta_input']['application'] = sanitize_textarea_field($_POST['application_id']); }
    if (isset($_POST['listing_id']))     { $sanitized_args['meta_input']['listing']     = sanitize_textarea_field($_POST['listing_id']); }
    if (isset($_POST['message']))        { $sanitized_args['meta_input']['message']     = sanitize_textarea_field($_POST['message']); }
    if (isset($_POST['status']))         { $sanitized_args['meta_input']['status']      = sanitize_text_field($_POST['status']); }

    return $sanitized_args;
}
