<?php

$page = $_GET['page'] ?? 1;

$listing_filter = !empty($_GET['filter_listing']) && $_GET['filter_listing'] !== 'all'
    ? [(int) $_GET['filter_listing']]
    : null;

$args = ['page' => $page];

if ($listing_filter) {
    $args['listing_ids'] = $listing_filter;
}

if (!empty($_GET['filter_status']) && $_GET['filter_status'] !== 'all') {
    $args['status'] = $_GET['filter_status'];
}

$result = get_user_application_submissions($args);

$submissions   = $result['submissions'];
$max_num_pages = $result['max_num_pages'];
$is_last_page  = $page == $max_num_pages;
$next_page     = $result['next_page'];

if (count($submissions) > 0) {
    foreach ($submissions as $index => $submission) {
        get_template_part('template-parts/cards/submitted-application-card', '', [
            'submission'   => $submission,
            'last'         => $index == array_key_last($submissions),
            'is_last_page' => $is_last_page,
            'next_page'    => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/empty-states/no-submitted-applications', '', []);
}
