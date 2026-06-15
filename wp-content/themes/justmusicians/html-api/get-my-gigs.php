<?php

$page = $_GET['page'] ?? 1;
$result = get_user_listing_proposals([
    'page' => $page
]);

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
