<?php

$page = $_GET['page'] ?? 1;

$args = ['page' => $page];

if (!empty($_GET['date_range'])) {
    $today = gmdate('Y-m-d');
    if ($_GET['date_range'] === 'upcoming') {
        $args['start_date_after'] = $today;
    } elseif ($_GET['date_range'] === 'past') {
        $args['start_date_before'] = $today;
    }
}

$result = get_user_events($args);

$events        = $result['events'];
$max_num_pages = $result['max_num_pages'];
$is_last_page  = $page == $max_num_pages;
$next_page     = $result['next_page'];

if (count($events) > 0) {
    foreach ($events as $index => $event) {
        $event_id = $event['post_id'];

        get_template_part('template-parts/cards/event-card', '', [
            'event_id'     => $event_id,
            'event_name'   => $event['event_name'],
            'permalink'    => $event['permalink'],
            'start_date'   => get_field('start_date', $event_id),
            'end_date'     => get_field('end_date', $event_id),
            'start_time'   => get_field('start_time', $event_id),
            'end_time'     => get_field('end_time', $event_id),
            'city'         => get_field('city', $event_id),
            'state'        => get_field('state', $event_id),
            'details'      => get_field('details', $event_id),
            'budget'       => get_field('budget', $event_id),
            'compensation' => get_field('compensation', $event_id),
            'proposals'    => hm_get_proposal_ids_by_event_id($event_id),
            'last'         => $index == array_key_last($events),
            'is_last_page' => $is_last_page,
            'next_page'    => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/no-results-content/no-events', '', []);
}
