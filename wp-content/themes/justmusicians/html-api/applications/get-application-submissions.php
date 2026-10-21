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
        $application_id = $submission['application_id'] ?? 0;
        $listing_id     = $submission['listing_id'] ?? 0;
        $events         = get_application_events($application_id);
        $event_data     = [];

        if (!empty($events) && $listing_id) {
            foreach ($events as $event) {
                $event_id = $event['event_id'] ?? 0;
                if (!$event_id) { continue; }

                $proposal_id = hm_proposal_exists($listing_id, $event_id);
                if ($proposal_id) {
                    $event_data[] = [
                        'event_id'     => $event_id,
                        'event_name'   => $event['event_name'],
                        'start_date'   => $event['start_date'],
                        'availability' => get_post_meta($proposal_id, 'availability', true),
                        'proposal_id'  => $proposal_id,
                    ];
                }
            }
        }

        get_template_part('template-parts/cards/submitted-application-card', '', [
            'submission'      => $submission,
            'application_url' => get_musician_application_url($application_id),
            'events'          => $event_data,
            'last'            => $index == array_key_last($submissions),
            'is_last_page'    => $is_last_page,
            'next_page'       => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/empty-states/no-submitted-applications', '', []);
}
