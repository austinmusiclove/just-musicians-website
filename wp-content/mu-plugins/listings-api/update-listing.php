<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function update_listing($args) {

    // err if no id
    // put valid args into correct input
    // update post
    // add status complete or incomplete

    $listing_post = array(
        'ID'           => sanitize_text_field($args['post_id']),
        'post_status'  => 'publish',
        'post_type'    => 'listing',
        'meta_input'   => [],
        'tax_input'    => [],
    );
    // Name
    if (!empty($args['name'])) {
        $name = sanitize_text_field($args['name']);
        $listing_post['post_title'] = $name;
        $listing_post['meta_input']['name'] = $name;
    }

    // Meta Input
    if (!empty($args['description'])) { $listing_post['meta_input']['description'] = sanitize_text_field($args['description']); }
    if (!empty($args['city']))        { $listing_post['meta_input']['city']        = sanitize_text_field($args['city']); }
    if (!empty($args['state']))       { $listing_post['meta_input']['state']       = sanitize_text_field($args['state']); }
    if (!empty($args['zip_code']))    { $listing_post['meta_input']['zip_code']    = sanitize_text_field($args['zip_code']); }
    if (!empty($args['bio']))         { $listing_post['meta_input']['bio']         = sanitize_textarea_field($args['bio']); }
    // ensemble_size
    // venues_played_verified
    // venues_played_unverified_strings
    // draw
    // email
    // phone
    // website
    // instagram_handle
    // instagram_url
    // instagram is private
    // tiktok_handle
    // tiktok_url
    // x_handle
    // x_url
    // facebook_url
    // youtube_url
    // bandcamp_url
    // spotify_artist_url
    // spotify_artist_id
    // apple_music_artist_url
    // soundcloud_url
    // youtube_video_urls
    // unofficial_tags

    // Taxonomy Input
    //if (!empty($args['genres'])) { $listing_post['tax_input']['genre'] = $args['genres']; }
    wp_set_post_terms($args['post_id'], $args['categories'], 'mcategory');
    wp_set_post_terms($args['post_id'], $args['genres'], 'genre');
    wp_set_post_terms($args['post_id'], $args['subgenres'], 'subgenre');
    wp_set_post_terms($args['post_id'], $args['instrumentations'], 'instrumentation');
    wp_set_post_terms($args['post_id'], $args['settings'], 'setting');

    // handle thumbnail


    $result = wp_update_post($listing_post, true);
    if (is_wp_error($result)) {
        return $result->get_error_message();
    }
    return $result;
}

