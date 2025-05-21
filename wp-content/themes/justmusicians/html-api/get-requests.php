<?php

// Get Requests
$page = $_GET['page'] ?? 1;
$result = get_user_requests([
    'page' => $page,
]);

$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
$requests        = $result['requests'];

if (count($requests) > 0) {

    foreach ($requests as $index => $request) {
        echo get_template_part('template-parts/account/request-listing', '', [
            'post_id'               => $request['post_id'],
            'subject'               => $request['subject'],
            'date_type'             => $request['date_type'],
            'date'                  => $request['date'],
            'date_time_details'     => $request['date_time_details'],
            'time'                  => $request['time'],
            'zip_code'              => $request['zip_code'],
            'location_details'      => $request['location_details'],
            'duration'              => $request['duration'],
            'ensemble_size'         => $request['ensemble_size'],
            'equipment_requirement' => $request['equipment_requirement'],
            'equipment_details'     => $request['equipment_details'],
            'details'               => $request['details'],
            'genres'                => $request['genres'],
            'last'                  => $index == array_key_last($requests),
            'is_last_page'          => $is_last_page,
            'next_page'             => $next_page,
        ]);
    }

} else {
    echo get_template_part('template-parts/content/no-user-requests', '', []);
}
