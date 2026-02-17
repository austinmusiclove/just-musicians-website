<?php
// Handles arg parsing for reviews apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


// parses args from the request, sanitizes them, and returns args ready for wp_insert_post or wp_update_post
// if an arg is omitted in $_POST, it will be ommitted in the sanitized_args and therefore not altered in wp_update_post or wp_insert_post
function get_sanitized_review_args($review_post_type, $reviewee_id) {

    $sanitized_args = [
        'post_status'     => 'publish',
        'meta_input'      => [],
    ];

    // Post Id
    if (isset($reviewee_id))          { $sanitized_args['meta_input']['reviewee']  = sanitize_text_field($reviewee_id); }

    // Post Type
    if (isset($review_post_type))     { $sanitized_args['post_type']               = sanitize_text_field($review_post_type); }

    // Meta Fields
    if (isset($_POST['rating']))      { $sanitized_args['meta_input']['rating']    = sanitize_text_field($_POST['rating']); }
    if (isset($_POST['review_body'])) { $sanitized_args['meta_input']['review']    = sanitize_textarea_field($_POST['review_body']); }
    if (isset($_POST['reviewee']))    { $sanitized_args['meta_input']['reviewee']  = sanitize_text_field($_POST['reviewee']); }
    if (isset($_POST['author']))      { $sanitized_args['meta_input']['author']    = sanitize_text_field($_POST['author']); }

    // Title
    $author_display_name = "";
    $reviewee_name = "";
    if (isset($_POST['author_display_name'])) { $author_display_name = sanitize_text_field($_POST['author_display_name']); }
    if (isset($_POST['reviewee_name']))       { $reviewee_name       = sanitize_text_field($_POST['reviewee_name']); }
    $sanitized_args['post_title'] = $sanitized_args['meta_input']['rating'] . "/5 - " . $reviewee_name . ":" . $reviewee_id . " - " . $author_display_name . ":" . $sanitized_args['meta_input']['author'];

    return $sanitized_args;
}
