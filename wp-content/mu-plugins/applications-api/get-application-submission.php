<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_application_submission($application_id, $listing_id) {
    $existing = get_posts([
        'post_type'      => 'app_submission',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => [
            ['key' => 'application', 'value' => $application_id],
            ['key' => 'listing',     'value' => $listing_id],
        ],
    ]);

    return !empty($existing) ? $existing[0] : null;
}
