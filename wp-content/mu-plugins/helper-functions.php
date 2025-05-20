<?php
function user_logged_in() {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in to perform this action.', ['status' => 401]);
    }
    return true;
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

function get_thumbnails_from_listings($listing_post_ids) {
    $thumbnails = [];
    if (count($listing_post_ids) >= 4) {
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[1], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[2], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[3], 'standard-listing');
    } else if (count($listing_post_ids) >= 1) {
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing');
    }
    return array_filter($thumbnails);
}
