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

if (!empty($_GET['date_range'])) {
    $today = gmdate('Y-m-d');
    if ($_GET['date_range'] === 'upcoming') {
        $args['start_date_after'] = $today;
    } elseif ($_GET['date_range'] === 'past') {
        $args['start_date_before'] = $today;
    }
}

$result = get_user_listing_proposals($args);

$proposals       = $result['proposals'];
$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];

if (count($proposals) > 0) {
    foreach ($proposals as $index => $proposal) {
        get_template_part('template-parts/account/gig-listing', '', [
            'proposal'     => $proposal,
            'last'         => $index == array_key_last($proposals),
            'is_last_page' => $is_last_page,
            'next_page'    => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/content/no-gigs', '', []);
}
