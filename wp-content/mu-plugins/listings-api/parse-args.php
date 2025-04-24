<?php
// Handles arg parsing for listing apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
// for taxonomy args you can pass in [""] if you want to remove all terms; omitting the arg will not alter the terms
function get_sanitized_listing_args() {
    $sanitized_args = [
        'post_type'       => 'listing',
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                { $sanitized_args['ID']                                   = sanitize_text_field($_POST['post_id']); }

    // Name
    if (isset($_POST['listing_name']))           { $sanitized_args['post_title']                           = sanitize_text_field($_POST['listing_name']); }
    if (isset($_POST['listing_name']))           { $sanitized_args['meta_input']['name']                   = sanitize_text_field($_POST['listing_name']); }

    // Meta Fields
    if (isset($_POST['description']))            { $sanitized_args['meta_input']['description']            = sanitize_text_field($_POST['description']); }
    if (isset($_POST['city']))                   { $sanitized_args['meta_input']['city']                   = sanitize_text_field($_POST['city']); }
    if (isset($_POST['state']))                  { $sanitized_args['meta_input']['state']                  = sanitize_text_field($_POST['state']); }
    if (isset($_POST['zip_code']))               { $sanitized_args['meta_input']['zip_code']               = sanitize_text_field($_POST['zip_code']); }
    if (isset($_POST['bio']))                    { $sanitized_args['meta_input']['bio']                    = sanitize_textarea_field($_POST['bio']); }
    if (isset($_POST['listing_email']))          { $sanitized_args['meta_input']['email']                  = sanitize_email($_POST['listing_email']); }
    if (isset($_POST['phone']))                  { $sanitized_args['meta_input']['phone']                  = sanitize_text_field($_POST['phone']); }
    if (isset($_POST['instagram_handle']))       { $sanitized_args['meta_input']['instagram_handle']       = sanitize_text_field($_POST['instagram_handle']); }
    if (isset($_POST['instagram_url']))          { $sanitized_args['meta_input']['instagram_url']          = sanitize_url($_POST['instagram_url']); }
    if (isset($_POST['tiktok_handle']))          { $sanitized_args['meta_input']['tiktok_handle']          = sanitize_text_field($_POST['tiktok_handle']); }
    if (isset($_POST['tiktok_url']))             { $sanitized_args['meta_input']['tiktok_url']             = sanitize_url($_POST['tiktok_url']); }
    if (isset($_POST['x_handle']))               { $sanitized_args['meta_input']['x_handle']               = sanitize_text_field($_POST['x_handle']); }
    if (isset($_POST['x_url']))                  { $sanitized_args['meta_input']['x_url']                  = sanitize_url($_POST['x_url']); }
    if (isset($_POST['website']))                { $sanitized_args['meta_input']['website']                = sanitize_url($_POST['website']); }
    if (isset($_POST['facebook_url']))           { $sanitized_args['meta_input']['facebook_url']           = sanitize_url($_POST['facebook_url']); }
    if (isset($_POST['youtube_url']))            { $sanitized_args['meta_input']['youtube_url']            = sanitize_url($_POST['youtube_url']); }
    if (isset($_POST['bandcamp_url']))           { $sanitized_args['meta_input']['bandcamp_url']           = sanitize_url($_POST['bandcamp_url']); }
    if (isset($_POST['spotify_artist_url']))     { $sanitized_args['meta_input']['spotify_artist_url']     = sanitize_url($_POST['spotify_artist_url']); }
    if (isset($_POST['spotify_artist_id']))      { $sanitized_args['meta_input']['spotify_artist_id']      = sanitize_text_field($_POST['spotify_artist_id']); }
    if (isset($_POST['apple_music_artist_url'])) { $sanitized_args['meta_input']['apple_music_artist_url'] = sanitize_url($_POST['apple_music_artist_url']); }
    if (isset($_POST['soundcloud_url']))         { $sanitized_args['meta_input']['soundcloud_url']         = sanitize_url($_POST['soundcloud_url']); }
    if (isset($_POST['ensemble_size']))          { $sanitized_args['meta_input']['ensemble_size']          = custom_sanitize_array($_POST['ensemble_size']); }
    if (isset($_POST['youtube_video_urls']))     { $sanitized_args['meta_input']['youtube_video_urls']     = custom_sanitize_array($_POST['youtube_video_urls']); }

    // Taxonomies
    if (isset($_POST['categories']) )            { $sanitized_args['tax_input']['mcategory']               = custom_sanitize_array($_POST['categories']); }
    if (isset($_POST['genres']))                 { $sanitized_args['tax_input']['genre']                   = custom_sanitize_array($_POST['genres']); }
    if (isset($_POST['subgenres']))              { $sanitized_args['tax_input']['subgenre']                = custom_sanitize_array($_POST['subgenres']); }
    if (isset($_POST['instrumentations']))       { $sanitized_args['tax_input']['instrumentation']         = custom_sanitize_array($_POST['instrumentations']); }
    if (isset($_POST['settings']))               { $sanitized_args['tax_input']['setting']                 = custom_sanitize_array($_POST['settings']); }
    if (isset($_POST['keywords']))               { $sanitized_args['tax_input']['keyword']                 = custom_sanitize_array($_POST['keywords']); }

    // Files
    if (isset($_FILES['cropped-thumbnail']))     { $sanitized_args['_thumbnail_file']                      = custom_sanitize_file($_FILES['cropped-thumbnail']); }


    return $sanitized_args;
}


// Cleans name property of file
function custom_sanitize_file($file) {
    $file['name'] = sanitize_file_name($file['name']);
    return $file;
}

// sanitize array, remove blank values with array_filter, reindex array with array_values
// useful with array inputs where i always pass a blank so that the user has a way to erase all options; otherwise no argument is passed to the back end and no edit happens
// reindexing is useful so that json_encode turns it into an array instead of an object
function custom_sanitize_array($arr) {
    return array_values(array_filter(array_map('sanitize_text_field', rest_sanitize_array($arr))));
}
