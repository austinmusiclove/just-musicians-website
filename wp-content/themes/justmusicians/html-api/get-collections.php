<?php

// Get Collections
$page = $_GET['page'] ?? 1;
$result = get_collections([
    'page' => $page,
]);

$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
$collections     = $result['collections'];

foreach ($collections as $index => $collection) {
    echo get_template_part('template-parts/account/collection-listing', '', [
        'post_id'        => $collection['post_id'],
        'name'           => $collection['name'],
        'thumbnail_urls' => $collection['thumbnail_urls'],
        'num_listings'   => count($collection['listings']),
        'permalink'      => $collection['permalink'],
        'allow_delete'   => $collection['post_id'] != 'favorites',
        'last'           => $index == array_key_last($collections),
        'is_last_page'   => $is_last_page,
        'next_page'      => $next_page,
    ]);
}
