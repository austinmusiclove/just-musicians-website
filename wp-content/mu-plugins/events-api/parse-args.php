<?php
// Handles arg parsing for event apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
function get_sanitized_event_args($is_update) {
    $sanitized_args = [
        'post_type'       => 'event',
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                             { $sanitized_args['ID']                                = sanitize_text_field($_POST['post_id']); }

    // Title
    if (isset($_POST['event_name']))                          { $sanitized_args['post_title']                        = sanitize_text_field($_POST['event_name']); }
    if (isset($_POST['event_name']))                          { $sanitized_args['meta_input']['event_name']          = sanitize_text_field($_POST['event_name']); }

    // Meta Fields
    if (isset($_POST['event_start_date']))                    { $sanitized_args['meta_input']['start_date']          = sanitize_text_field($_POST['event_start_date']); }
    if (isset($_POST['event_end_date']))                      { $sanitized_args['meta_input']['end_date']            = sanitize_text_field($_POST['event_end_date']); }
    if (isset($_POST['event_start_time']))                    { $sanitized_args['meta_input']['start_time']          = sanitize_text_field($_POST['event_start_time']); }
    if (isset($_POST['event_end_time']))                      { $sanitized_args['meta_input']['end_time']            = sanitize_text_field($_POST['event_end_time']); }
    if (isset($_POST['event_address_line_1']))                { $sanitized_args['meta_input']['address_line_1']      = sanitize_text_field($_POST['event_address_line_1']); }
    if (isset($_POST['event_address_line_2']))                { $sanitized_args['meta_input']['address_line_2']      = sanitize_text_field($_POST['event_address_line_2']); }
    if (isset($_POST['event_city']))                          { $sanitized_args['meta_input']['city']                = sanitize_text_field($_POST['event_city']); }
    if (isset($_POST['event_state']))                         { $sanitized_args['meta_input']['state']               = sanitize_text_field($_POST['event_state']); }
    if (isset($_POST['event_zip_code']))                      { $sanitized_args['meta_input']['zip_code']            = sanitize_text_field($_POST['event_zip_code']); }
    if (isset($_POST['event_lat']))                           { $sanitized_args['meta_input']['latitude']            = sanitize_text_field($_POST['event_lat']); }
    if (isset($_POST['event_lng']))                           { $sanitized_args['meta_input']['longitude']           = sanitize_text_field($_POST['event_lng']); }
    if (isset($_POST['event_budget']))                        { $sanitized_args['meta_input']['budget']              = sanitize_text_field($_POST['event_budget']); }
    if (isset($_POST['event_compensation']))                  { $sanitized_args['meta_input']['compensation']        = sanitize_text_field($_POST['event_compensation']); }
    if (isset($_POST['event_details']))                       { $sanitized_args['meta_input']['details']             = sanitize_textarea_field($_POST['event_details']); }
    if (isset($_POST['event_request_quote']))                 { $sanitized_args['meta_input']['request_quote']       = rest_sanitize_boolean($_POST['event_request_quote']); }
    if (isset($_POST['event_request_draw']))                  { $sanitized_args['meta_input']['request_draw']        = rest_sanitize_boolean($_POST['event_request_draw']); }
    if (isset($_POST['event_auto_rfp']))                      { $sanitized_args['meta_input']['auto_rfp']            = rest_sanitize_boolean($_POST['event_auto_rfp']); }

    // Taxonomies
    if (isset($_POST['event_genres']))                        { $sanitized_args['tax_input']['genre']                = custom_sanitize_array($_POST['event_genres']); }
    if (isset($_POST['event_ensemble_size']))                 { $sanitized_args['tax_input']['ensemble_size']        = custom_sanitize_array($_POST['event_ensemble_size']); }
    if (isset($_POST['inquiry_listing']))                     { $sanitized_args['inquiry_listing']                   = custom_sanitize_listing_inquiry($_POST['inquiry_listing']); }

    return $sanitized_args;
}

function custom_sanitize_listing_inquiry($input) {
    $listing_id = sanitize_text_field($input);
    if ($listing_id && get_post_type($listing_id) === 'listing' && get_post_status($listing_id) === 'publish') {
        return $listing_id;
    }
    return null;
}
