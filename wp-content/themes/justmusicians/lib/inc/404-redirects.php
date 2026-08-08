<?php

function force_404_for_private_post_types() {
    if (
        is_singular('proposal') or
        is_singular('app_submission') or
        is_singular('offer') or
        is_singular('youtubevideo') or
        is_singular('artist') or
        is_singular('performance') or
        is_singular('listing_review') or
        is_singular('buyer_review') or
        is_singular('venue_review') or
        is_singular('comp_report') or
        is_singular('review_submission') or
        is_singular('tmp_code')
    ) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}
add_action('template_redirect', 'force_404_for_private_post_types');
