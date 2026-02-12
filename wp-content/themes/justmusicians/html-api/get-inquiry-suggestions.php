<?php

// Get listings
$page             = $_GET['page'] ?? 1;
$inquiry_id       = get_query_var('inquiry-id');
$ensemble_size    = get_post_meta($inquiry_id, 'ensemble_size', true);
$inquiry_genres   = wp_get_post_terms($inquiry_id, 'genre', ['fields' => 'names']);
$listings_invited = get_post_meta($inquiry_id, 'listings_invited', true);
$listings_invited = is_array($listings_invited) ? $listings_invited : [];

$result = get_listings([
    'ensemble_size' => $ensemble_size,
    'genres'        => $inquiry_genres,
    'exclude'       => $listings_invited,
]);


$listings        = $result['listings'];
$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
$results_label   = $max_num_results == 1 ? ' Listing' : ' Listings';


// Render listings
if (count($listings) > 0) {
    foreach($listings as $index => $listing) {
        get_template_part('template-parts/listings/suggestion-listing', '', [
            'post_id'                => $listing['post_id'],
            'inquiry_id'             => $inquiry_id,
            'name'                   => $listing['name'],
            'rating'                 => $listing['rating'],
            'review_count'           => $listing['review_count'],
            'location'               => $listing['city'] . ', ' . $listing['state'],
            'description'            => $listing['description'],
            'thumbnail_url'          => $listing['thumbnail_url'],
            'youtube_video_data'     => $listing['youtube_video_data'],
            'verified'               => $listing['verified'],
            'permalink'              => $listing['permalink'],
            'lazyload_thumbnail'     => $index >= 3,
            'last'                   => $index == array_key_last($listings),
            'is_last_page'           => $is_last_page,
            'next_page'              => $next_page,
        ]);
    }

} else if ($page == 1) {
    get_template_part( 'template-parts/content/no-suggestion-listings');
}
