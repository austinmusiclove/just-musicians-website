<?php

// Get Inquiries
$page = $_GET['page'] ?? 1;
$result = get_user_inquiries([
    'page' => $page,
]);

$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
$inquiries       = $result['inquiries'];

if (count($inquiries) > 0) {

    foreach ($inquiries as $index => $inquiry) {
        echo get_template_part('template-parts/account/inquiry-listing', '', [
            'post_id'        => $inquiry['post_id'],
            'subject'        => $inquiry['subject'],
            'thumbnail_urls' => $inquiry['thumbnail_urls'],
            'num_listings'   => count($inquiry['listings']),
            'permalink'      => $inquiry['permalink'],
            'allow_delete'   => true,
            'last'           => $index == array_key_last($inquiries),
            'is_last_page'   => $is_last_page,
            'next_page'      => $next_page,
        ]);
    }

} else {
    echo get_template_part('template-parts/content/no-user-inquiries', '', []);
}
