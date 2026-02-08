<?php
// Handles arg parsing for compensation reports apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
// for taxonomy args you can pass in [""] if you want to remove all terms; omitting the arg will not alter the terms
function get_sanitized_compensation_report_args() {

    $validation = validate_compensation_report_args();
    if ( is_wp_error($validation) ) {
        return $validation;
    }

    $sanitized_args = [
        'post_type'       => 'comp_report',
        'post_status'     => 'pending',
        'meta_input'      => [],
        'tax_input'       => [],
    ];

    // Post Id
    if (isset($_POST['post_id']))             { $sanitized_args['ID']                                = sanitize_text_field($_POST['post_id']); }

    // Post Status
    if (!empty($_POST['post_status']))        { $sanitized_args['post_status']                       = sanitize_text_field($_POST['post_status']); }

    // Meta Fields
    if (isset($_POST['venue_id']))            { $sanitized_args['meta_input']['venue']               = sanitize_text_field($_POST['venue_id']); }
    if (isset($_POST['venue_id']))            { $sanitized_args['meta_input']['venue_post_id']       = sanitize_text_field($_POST['venue_id']); }
    if (isset($_POST['venue_name']))          { $sanitized_args['meta_input']['venue_name']          = sanitize_text_field($_POST['venue_name']); }
    if (isset($_POST['total_earnings']))      { $sanitized_args['meta_input']['total_earnings']      = sanitize_text_field($_POST['total_earnings']); }
    if (isset($_POST['minutes_performed']))   { $sanitized_args['meta_input']['minutes_performed']   = sanitize_text_field($_POST['minutes_performed']); }
    if (isset($_POST['total_performers']))    { $sanitized_args['meta_input']['total_performers']    = sanitize_text_field($_POST['total_performers']); }
    if (isset($_POST['comp_structure']))      { $sanitized_args['meta_input']['comp_structure']      = sanitize_text_field($_POST['comp_structure']); }
    if (isset($_POST['payment_speed']))       { $sanitized_args['meta_input']['payment_speed']       = sanitize_text_field($_POST['payment_speed']); }
    if (isset($_POST['payment_method']))      { $sanitized_args['meta_input']['payment_method']      = sanitize_text_field($_POST['payment_method']); }
    if (isset($_POST['review']))              { $sanitized_args['meta_input']['review']              = sanitize_text_field($_POST['review']); }
    if (isset($_POST['performing_act_name'])) { $sanitized_args['meta_input']['performing_act_name'] = sanitize_text_field($_POST['performing_act_name']); }
    if (isset($_POST['performance_date']))    { $sanitized_args['meta_input']['performance_date']    = sanitize_text_field($_POST['performance_date']); }
    if (isset($_POST['show_flier_url']))      { $sanitized_args['meta_input']['show_flier_url']      = sanitize_text_field($_POST['show_flier_url']); }
    if (isset($_POST['performance']))         { $sanitized_args['meta_input']['performance']         = sanitize_text_field($_POST['performance']); }

    // Author email
    $current_user = wp_get_current_user(); // will be empty string if user not logged in
    $sanitized_args['meta_input']['author_email'] = $current_user->user_email;


    return $sanitized_args;
}
