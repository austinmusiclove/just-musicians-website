<?php

$event_id = get_query_var('event-id');
$page     = $_GET['page'] ?? 1;
$per_page = 10;

$proposal_ids = hm_get_proposal_ids_by_event_id($event_id);

$total_ids     = count($proposal_ids);
$max_num_pages = max(1, ceil($total_ids / $per_page));
$offset        = ($page - 1) * $per_page;
$page_ids      = array_slice($proposal_ids, $offset, $per_page);
$is_last_page  = $page >= $max_num_pages;
$next_page     = $is_last_page ? null : (int) $page + 1;

if (!empty($page_ids)) {
    foreach ($page_ids as $index => $proposal_id) {
        $listing_id = (int) get_post_meta($proposal_id, 'listing', true);
        get_template_part('template-parts/cards/event-applicant-card', '', [
            'proposal_id'  => $proposal_id,
            'listing_id'   => $listing_id,
            'event_id'     => $event_id,
            'last'         => $index === array_key_last($page_ids),
            'is_last_page' => $is_last_page,
            'next_page'    => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/no-results-content/no-event-applicants', '', []);
}
