<?php
// Handles arg parsing for listing apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
// for taxonomy args you can pass in [""] if you want to remove all terms; omitting the arg will not alter the terms
function get_sanitized_listing_args() {

    $validation = validate_listing_args();
    if ( is_wp_error($validation) ) {
        return $validation;
    }

    $sanitized_args = [
        'post_type'       => 'listing',
        'post_status'     => 'publish',
        'meta_input'      => [],
        'tax_input'       => [],
    ];
    // Post Id
    if (isset($_POST['post_id']))                  { $sanitized_args['ID']                                     = sanitize_text_field($_POST['post_id']); }

    // Post Status
    if (!empty($_POST['post_status']))             { $sanitized_args['post_status']                            = sanitize_text_field($_POST['post_status']); }

    // Name
    if (isset($_POST['listing_name']))             { $sanitized_args['post_title']                             = sanitize_text_field($_POST['listing_name']); }
    if (isset($_POST['listing_name']))             { $sanitized_args['meta_input']['name']                     = sanitize_text_field($_POST['listing_name']); }

    // Meta Fields
    if (isset($_POST['description']))              { $sanitized_args['meta_input']['description']              = sanitize_text_field($_POST['description']); }
    if (isset($_POST['city']))                     { $sanitized_args['meta_input']['city']                     = sanitize_text_field($_POST['city']); }
    if (isset($_POST['state']))                    { $sanitized_args['meta_input']['state']                    = sanitize_text_field($_POST['state']); }
    if (isset($_POST['zip_code']))                 { $sanitized_args['meta_input']['zip_code']                 = sanitize_text_field($_POST['zip_code']); }
    if (isset($_POST['bio']))                      { $sanitized_args['meta_input']['bio']                      = sanitize_textarea_field($_POST['bio']); }
    if (isset($_POST['listing_email']))            { $sanitized_args['meta_input']['email']                    = sanitize_email($_POST['listing_email']); }
    if (isset($_POST['phone']))                    { $sanitized_args['meta_input']['phone']                    = sanitize_text_field($_POST['phone']); }
    if (isset($_POST['instagram_handle']))         { $sanitized_args['meta_input']['instagram_handle']         = sanitize_text_field($_POST['instagram_handle']); }
    if (isset($_POST['tiktok_handle']))            { $sanitized_args['meta_input']['tiktok_handle']            = sanitize_text_field($_POST['tiktok_handle']); }
    if (isset($_POST['x_handle']))                 { $sanitized_args['meta_input']['x_handle']                 = sanitize_text_field($_POST['x_handle']); }
    if (isset($_POST['instagram_url']))            { $sanitized_args['meta_input']['instagram_url']            = sanitize_url($_POST['instagram_url'],          ['https', 'http']); }
    if (isset($_POST['tiktok_url']))               { $sanitized_args['meta_input']['tiktok_url']               = sanitize_url($_POST['tiktok_url'],             ['https', 'http']); }
    if (isset($_POST['x_url']))                    { $sanitized_args['meta_input']['x_url']                    = sanitize_url($_POST['x_url'],                  ['https', 'http']); }
    if (isset($_POST['website']))                  { $sanitized_args['meta_input']['website']                  = sanitize_url($_POST['website'],                ['https', 'http']); }
    if (isset($_POST['facebook_url']))             { $sanitized_args['meta_input']['facebook_url']             = sanitize_url($_POST['facebook_url'],           ['https', 'http']); }
    if (isset($_POST['youtube_url']))              { $sanitized_args['meta_input']['youtube_url']              = sanitize_url($_POST['youtube_url'],            ['https', 'http']); }
    if (isset($_POST['bandcamp_url']))             { $sanitized_args['meta_input']['bandcamp_url']             = sanitize_url($_POST['bandcamp_url'],           ['https', 'http']); }
    if (isset($_POST['apple_music_artist_url']))   { $sanitized_args['meta_input']['apple_music_artist_url']   = sanitize_url($_POST['apple_music_artist_url'], ['https', 'http']); }
    if (isset($_POST['soundcloud_url']))           { $sanitized_args['meta_input']['soundcloud_url']           = sanitize_url($_POST['soundcloud_url'],         ['https', 'http']); }
    if (isset($_POST['spotify_artist_url']))       { $sanitized_args['meta_input']['spotify_artist_url']       = sanitize_url($_POST['spotify_artist_url'],     ['https', 'http']); }
    if (isset($_POST['spotify_artist_id']))        { $sanitized_args['meta_input']['spotify_artist_id']        = sanitize_text_field($_POST['spotify_artist_id']); }
    if (isset($_POST['min_ensemble_size']))        { $sanitized_args['meta_input']['min_ensemble_size']        = sanitize_text_field($_POST['min_ensemble_size']); }
    if (isset($_POST['max_ensemble_size']))        { $sanitized_args['meta_input']['max_ensemble_size']        = sanitize_text_field($_POST['max_ensemble_size']); }
    if (isset($_POST['ensemble_size']))            { $sanitized_args['meta_input']['ensemble_size']            = custom_sanitize_array($_POST['ensemble_size']); }
    if (isset($_POST['venues_played_verified']))   { $sanitized_args['meta_input']['venues_played_verified']   = custom_sanitize_array($_POST['venues_played_verified']); }
    if (isset($_POST['venues_played_unverified'])) { $sanitized_args['meta_input']['venues_played_unverified'] = custom_sanitize_array($_POST['venues_played_unverified']); }

    // Taxonomies
    if (isset($_POST['categories']) )              { $sanitized_args['tax_input']['mcategory']                 = custom_sanitize_array($_POST['categories']); }
    if (isset($_POST['genres']))                   { $sanitized_args['tax_input']['genre']                     = custom_sanitize_array($_POST['genres']); }
    if (isset($_POST['subgenres']))                { $sanitized_args['tax_input']['subgenre']                  = custom_sanitize_array($_POST['subgenres']); }
    if (isset($_POST['instrumentations']))         { $sanitized_args['tax_input']['instrumentation']           = custom_sanitize_array($_POST['instrumentations']); }
    if (isset($_POST['settings']))                 { $sanitized_args['tax_input']['setting']                   = custom_sanitize_array($_POST['settings']); }
    if (isset($_POST['keywords']))                 { $sanitized_args['tax_input']['keyword']                   = custom_sanitize_array($_POST['keywords']); }
    if (isset($_POST['mediatags']))                { $sanitized_args['tax_input']['mediatag']                  = custom_parse_json($_POST['mediatags']); }

    // Media
    if (isset($_POST['cover_image_meta']))         { $sanitized_args['cover_image']                            = custom_parse_file($_POST['cover_image_meta'], 'cover_image'); }
    if (isset($_POST['listing_images_meta']))      { $sanitized_args['listing_images']                         = custom_parse_ordered_files($_POST['listing_images_meta'], 'listing_images'); }
    if (isset($_POST['stage_plots_meta']))         { $sanitized_args['stage_plots']                            = custom_parse_ordered_files($_POST['stage_plots_meta'], 'stage_plots'); }
    if (isset($_POST['youtube_video_data']))       { $sanitized_args['youtube_videos']                         = custom_parse_youtube_video_data($_POST['youtube_video_data']); }


    return $sanitized_args;
}


// Parse files and file meta data
function custom_parse_file($data, $file_index) {
    $data = custom_parse_json($data);
    if (isset($_FILES[$file_index])) {
        $file['name'] = sanitize_file_name($_FILES[$file_index]['name']);
        $data['file'] = $_FILES[$file_index];
    }
    return $data;
}
function custom_parse_ordered_files($data, $file_index) {
    $ordered_files = [];

    // Parse meta data and files
    $data = custom_parse_json($data);
    $parsed_files = parse_files($file_index);

    // if has upload index add file from that index
    foreach ($data as $image_data) {
        if (isset($image_data['upload_index']) and is_int($image_data['upload_index']) and $image_data['upload_index'] < count($parsed_files)) {
            $image_data['file'] = $parsed_files[$image_data['upload_index']];
        }
        $ordered_files[] = $image_data;
    }
    return $ordered_files;
}
function parse_files($file_index) {
    $parsed_files = [];;
    if (isset($_FILES[$file_index])) {
        $files = $_FILES[$file_index];
        $count = count($files['name']);
        for ($iter = 0; $iter < $count; $iter++) {
            $parsed_files[] = [
                'name'     => sanitize_file_name($files['name'][$iter]),
                'type'     => $files['type'][$iter],
                'tmp_name' => $files['tmp_name'][$iter],
                'error'    => $files['error'][$iter],
                'size'     => $files['size'][$iter],
            ];
        }
    }
    return $parsed_files;
}
function custom_parse_youtube_video_data($json) {
    // TODO check youtube url validity
    // TODO generate video id from valid urls
    // TODO check post id matches other data or populate data from post id if exists
    // this is the way i was getting the id from url and there is another way in helper.php
    /*
    $youtube_video_data = custom_parse_json($json);
    $youtube_video_ids = [];
    if ($youtube_video_data and is_array($youtube_video_data)) {
        foreach($youtube_video_data as $video_data) {
            error_log(print_r($video_data, true));
            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/.+\/|\S+\?)(?:[^&]*&)*v=|youtu\.be\/)([a-zA-Z0-9_-]{11})(?=&|$)/', $video_data['url'], $matches)) {
                $youtube_video_ids[] = $matches[1];
            }
        }
    }
    error_log(print_r($youtube_video_ids, true));
    */
    return custom_parse_json($json);
}
function custom_parse_json($json) {
    return json_decode(stripslashes($json), true);
}


// sanitize array, remove blank values with array_filter, reindex array with array_values
// useful with array inputs where i always pass a blank so that the user has a way to erase all options; otherwise no argument is passed to the back end and no edit happens
// reindexing is useful so that json_encode turns it into an array instead of an object
function custom_sanitize_array($arr) {
    return array_values(array_filter(array_map('sanitize_text_field', rest_sanitize_array($arr))));
}
