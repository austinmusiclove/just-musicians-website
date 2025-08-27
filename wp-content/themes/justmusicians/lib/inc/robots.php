<?php

function noindex_specific_post_type($robots) {
    if (
        is_singular('collection') or
        is_singular('inquiry') or
        is_singular('youtubevideo') or
        is_singular('artist') or
        is_singular('performance') or
        is_singular('venue') or
        is_singular('venue_review') or
        is_singular('review_submission')
    ) {
        $robots['index'] = 'noindex';
    }
    return $robots;
}
add_filter('wp_robots', 'noindex_specific_post_type');
