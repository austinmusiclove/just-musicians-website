<?php

function noindex_specific_post_type($robots) {
    if (
        is_singular('collection') or
        is_singular('inquiry') or
        is_singular('youtubevideo') or
        is_singular('artist') or
        is_singular('performance') or
        is_singular('venue') or
        is_singular('listing_review') or
        is_singular('buyer_review') or
        is_singular('venue_review') or
        is_singular('comp_report') or
        is_singular('review_submission') or // Keep this old post type unless all review submission posts are deleted
        is_page('account') or
        is_page('listings') or
        is_page('collections') or
        is_page('inquiries') or
        is_page('messages')
    ) {
        $robots['index'] = 'noindex';
    }
    return $robots;
}
add_filter('wp_robots', 'noindex_specific_post_type');
