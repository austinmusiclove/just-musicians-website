<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_sanitized_application_args() {
    $sanitized_args = [
        'post_type'   => 'application',
        'post_status' => 'publish',
        'meta_input'  => [],
    ];

    if (isset($_POST['post_id']))                   { $sanitized_args['ID']                          = sanitize_text_field($_POST['post_id']); }
    if (isset($_POST['title']))                     { $sanitized_args['post_title']                  = sanitize_text_field($_POST['title']); }
    if (isset($_POST['title']))                     { $sanitized_args['meta_input']['title']         = sanitize_text_field($_POST['title']); }
    if (isset($_POST['description']))               { $sanitized_args['meta_input']['description']   = wp_kses_post($_POST['description']); }
    if (isset($_POST['events']))                    { $sanitized_args['meta_input']['events']        = custom_sanitize_int_array($_POST['events']); }
    if (isset($_POST['application_request_quote'])) { $sanitized_args['meta_input']['request_quote'] = rest_sanitize_boolean($_POST['application_request_quote']); }
    if (isset($_POST['application_request_draw']))  { $sanitized_args['meta_input']['request_draw']  = rest_sanitize_boolean($_POST['application_request_draw']); }

    return $sanitized_args;
}

function get_sanitized_application_submission_args() {
    $sanitized_args = [
        'post_type'   => 'app_submission',
        'post_status' => 'publish',
        'meta_input' => [],
    ];

    if (isset($_POST['application_id']))     { $sanitized_args['meta_input']['application']        = sanitize_textarea_field($_POST['application_id']); }
    if (isset($_POST['listing_id']))         { $sanitized_args['meta_input']['listing']            = sanitize_textarea_field($_POST['listing_id']); }
    if (isset($_POST['applicant_message']))  { $sanitized_args['meta_input']['message']            = sanitize_textarea_field($_POST['applicant_message']); }
    if (isset($_POST['status']))             { $sanitized_args['meta_input']['status']             = sanitize_text_field($_POST['status']); }
    if (isset($_POST['event_availability'])) { $sanitized_args['meta_input']['event_availability'] = sanitize_event_availability($_POST['event_availability']); }
    if (isset($_POST['minimum_guarantee']))  { $sanitized_args['meta_input']['minimum_guarantee']  = sanitize_text_field($_POST['minimum_guarantee']); }
    if (isset($_POST['draw_estimate']))      { $sanitized_args['meta_input']['draw_estimate']      = sanitize_text_field($_POST['draw_estimate']); }

    return $sanitized_args;
}
