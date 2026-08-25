<?php

$page = $_GET['page'] ?? 1;

$user_app_ids = hm_get_subject_ids_for_user( get_current_user_id(), [HM_ACCESS_TYPE_VIEW, HM_ACCESS_TYPE_EDIT, HM_ACCESS_TYPE_OWNER], 'application');
$args = ['page' => $page, 'post_ids' => $user_app_ids];

$result = get_user_applications($args);

$applications  = $result['applications'];
$max_num_pages = $result['max_num_pages'];
$is_last_page  = $page == $max_num_pages;
$next_page     = $result['next_page'];

if (count($applications) > 0) {
    foreach ($applications as $index => $application) {
        get_template_part('template-parts/cards/application-card', '', [
            'post_id'            => $application['post_id'],
            'title'              => $application['title'],
            'description'        => $application['description'],
            'permalink'          => $application['permalink'],
            'app_submission_ids' => array_map('strval', $application['app_submission_ids']),
            'last'               => $index == array_key_last($applications),
            'is_last_page'       => $is_last_page,
            'next_page'          => $next_page,
        ]);
    }
} else if ($page == 1) {
    get_template_part('template-parts/global/empty-states/no-applications', '', []);
}
