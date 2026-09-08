<?php

// Get Collections
$page = $_GET['page'] ?? 1;
$result = get_user_collections([
    'page' => $page,
]);

$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
$collections     = $result['collections'];

foreach ($collections as $index => $collection) {
    echo get_template_part('template-parts/cards/collection-card', '', [
        'post_id'        => $collection['post_id'],
        'name'           => $collection['name'],
        'thumbnail_urls' => $collection['thumbnail_urls'],
        'num_listings'   => count($collection['listings']),
        'permalink'      => $collection['permalink'],
        'allow_delete'   => !empty($collection['post_id']) && hm_get_user_access_type(get_current_user_id(), (string) $collection['post_id'], 'collection') === HM_ACCESS_TYPE_OWNER,
        'last'           => $index == array_key_last($collections),
        'is_last_page'   => $is_last_page,
        'next_page'      => $next_page,
    ]);
}
