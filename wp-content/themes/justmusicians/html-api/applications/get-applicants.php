<?php

$application_id = get_query_var('application-id');
$filter_status  = $_GET['filter_status'] ?? 'active';
$page           = $_GET['page'] ?? 1;
$sort           = $_GET['sort'] ?? 'recent';
$per_page       = 10;

$result = get_applicants($application_id, [
    'status' => $filter_status,
    'sort'   => $sort,
    'page'   => $page,
]);

$submission_ids = $result['submission_ids'];
$max_num_pages  = $result['max_num_pages'];
$is_last_page   = $page == $max_num_pages;
$next_page      = $is_last_page ? null : (int) $page + 1;

if (!empty($submission_ids)) {
    foreach ($submission_ids as $index => $submission_id) {
        $listing_id = (int) get_post_meta($submission_id, 'listing', true);
        $listing    = $listing_id ? get_listing(['post_id' => $listing_id]) : [];

        get_template_part('template-parts/cards/applicant-card', '', [
            'application_id'      => $application_id,
            'listing_id'          => $listing_id,
            'submission_id'       => $submission_id,
            'submission_status'   => get_post_meta($submission_id, 'status', true),
            'submission_message'  => get_post_meta($submission_id, 'message', true),
            'submission_updated'  => get_the_modified_time('F j, Y', $submission_id),
            'name'                => $listing['name'] ?? '',
            'rating'              => $listing['rating'] ?? 0,
            'review_count'        => $listing['review_count'] ?? 0,
            'location'            => $listing['city'] . ', ' . $listing['state'],
            'description'         => $listing['description'] ?? '',
            'genres'              => $listing['genre'] ?? [],
            'thumbnail_url'       => $listing['thumbnail_url'] ?? '',
            'youtube_video_data'  => $listing['youtube_video_data'],
            'verified'            => $listing['verified'] ?? false,
            'permalink'           => $listing['permalink'] ?? '',
            'lazyload_thumbnail'  => $index >= 3,
            'last'                => $index === array_key_last($submission_ids),
            'is_last_page'        => $is_last_page,
            'next_page'           => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/empty-states/no-applicants', '', []);
}
if ($is_last_page) {
    get_template_part('template-parts/global/empty-states/no-more-applicants', '', []);
}
