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
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                  { $sanitized_args['ID']                                     = sanitize_text_field($_POST['post_id']); }

    // Post Status
    if (!empty($_POST['post_status']))             { $sanitized_args['post_status']                            = sanitize_text_field($_POST['post_status']); }

    // Meta Fields
    if (isset($_POST['venue_id']))                   { $sanitized_args['meta_input']['venue']                    = sanitize_text_field($_POST['venue_id']); }
    if (isset($_POST['venue_id']))                   { $sanitized_args['meta_input']['venue_post_id']            = sanitize_text_field($_POST['venue_id']); }
    //if (isset($_POST['description']))              { $sanitized_args['meta_input']['description']              = sanitize_text_field($_POST['description']); }
    //if (isset($_POST['bio']))                      { $sanitized_args['meta_input']['bio']                      = sanitize_textarea_field($_POST['bio']); }
    //if (isset($_POST['listing_email']))            { $sanitized_args['meta_input']['email']                    = sanitize_email($_POST['listing_email']); }
    //if (isset($_POST['instagram_url']))            { $sanitized_args['meta_input']['instagram_url']            = sanitize_url($_POST['instagram_url'],          ['https', 'http']); }
    //if (isset($_POST['ensemble_size']))            { $sanitized_args['meta_input']['ensemble_size']            = custom_sanitize_array($_POST['ensemble_size']); }


    return $sanitized_args;
}
