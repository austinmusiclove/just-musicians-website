<?php
// Handles arg parsing for inquiry apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
function get_sanitized_inquiry_args() {
    $sanitized_args = [
        'post_type'       => 'inquiry',
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                             { $sanitized_args['ID']                                        = sanitize_text_field($_POST['post_id']); }

    // Title
    if (isset($_POST['inquiry_subject']))                     { $sanitized_args['post_title']                                = sanitize_text_field($_POST['inquiry_subject']); }
    if (isset($_POST['inquiry_subject']))                     { $sanitized_args['meta_input']['subject']                     = sanitize_text_field($_POST['inquiry_subject']); }

    // Meta Fields
    if (isset($_POST['inquiry_date_type']))                   { $sanitized_args['meta_input']['date_type']                   = sanitize_text_field($_POST['inquiry_date_type']); }
    if (isset($_POST['inquiry_date']))                        { $sanitized_args['meta_input']['date']                        = sanitize_text_field($_POST['inquiry_date']); }
    if (isset($_POST['inquiry_time']))                        { $sanitized_args['meta_input']['time']                        = sanitize_text_field($_POST['inquiry_time']); }
    if (isset($_POST['inquiry_zip_code']))                    { $sanitized_args['meta_input']['zip_code']                    = sanitize_text_field($_POST['inquiry_zip_code']); }
    if (isset($_POST['inquiry_budget_type']))                 { $sanitized_args['meta_input']['budget_type']                 = sanitize_text_field($_POST['inquiry_budget_type']); }
    if (isset($_POST['inquiry_budget']))                      { $sanitized_args['meta_input']['budget']                      = sanitize_text_field($_POST['inquiry_budget']); }
    if (isset($_POST['inquiry_percent_of_door']))             { $sanitized_args['meta_input']['percent_of_door']             = sanitize_text_field($_POST['inquiry_percent_of_door']); }
    if (isset($_POST['inquiry_percent_of_bar']))              { $sanitized_args['meta_input']['percent_of_bar']              = sanitize_text_field($_POST['inquiry_percent_of_bar']); }
    //if (isset($_POST['inquiry_duration']))                    { $sanitized_args['meta_input']['duration']                    = sanitize_text_field($_POST['inquiry_duration']); }
    //if (isset($_POST['inquiry_equipment_requirement']))       { $sanitized_args['meta_input']['equipment_requirement']       = sanitize_text_field($_POST['inquiry_equipment_requirement']); }
    //if (isset($_POST['inquiry_equipment_details']))           { $sanitized_args['meta_input']['equipment_details']           = sanitize_text_field($_POST['inquiry_equipment_details']); }
    if (isset($_POST['inquiry_max_listing_invites']))         { $sanitized_args['meta_input']['max_listing_invites']         = sanitize_text_field($_POST['inquiry_max_listing_invites']); }
    if (!isset($_POST['inquiry_max_listing_invites']))        { $sanitized_args['meta_input']['max_listing_invites']         = DEFAULT_QUOTES_REQUESTED; }
    if (isset($_POST['inquiry_ensemble_size']))               { $sanitized_args['meta_input']['ensemble_size']               = custom_sanitize_array($_POST['inquiry_ensemble_size']); }
    //if (isset($_POST['inquiry_date_time_details']))           { $sanitized_args['meta_input']['date_time_details']           = sanitize_textarea_field($_POST['inquiry_date_time_details']); }
    //if (isset($_POST['inquiry_location_details']))            { $sanitized_args['meta_input']['location_details']            = sanitize_textarea_field($_POST['inquiry_location_details']); }
    if (isset($_POST['inquiry_details']))                     { $sanitized_args['meta_input']['details']                     = sanitize_textarea_field($_POST['inquiry_details']); }

    // Taxonomies
    if (isset($_POST['inquiry_genres']))                      { $sanitized_args['tax_input']['genre']                        = custom_sanitize_array($_POST['inquiry_genres']); }

    // Add listing to the inquiry if provided
    if (isset($_POST['inquiry_listing'])) {
        $listing_id = sanitize_text_field($_POST['inquiry_listing']);
        if ($listing_id && get_post_type($listing_id) === 'listing' && get_post_status($listing_id) === 'publish') {
            $sanitized_args['meta_input']['listings_invited'] = [$listing_id];
        }
    }

    return $sanitized_args;
}
