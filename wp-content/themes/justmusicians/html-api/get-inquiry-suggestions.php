<?php

// Get listings
$page             = $_GET['page'] ?? 1;
$event_id         = get_query_var('event-id');
$inquiry_lat      = get_post_meta($event_id, 'latitude', true);
$inquiry_lng      = get_post_meta($event_id, 'longitude', true);
$ensemble_size    = wp_get_post_terms($event_id, 'ensemble_size', ['fields' => 'names']);
$inquiry_genres   = wp_get_post_terms($event_id, 'genre', ['fields' => 'names']);
$listings_invited = hm_get_listing_ids_by_event_id($event_id);
$listings_invited = is_array($listings_invited) ? $listings_invited : [];

$result = get_listings([
    'lat'           => $inquiry_lat,
    'lng'           => $inquiry_lng,
    'ensemble_size' => $ensemble_size,
    'genres'        => $inquiry_genres,
    'exclude'       => $listings_invited,
]);


$listings        = $result['listings'];
$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];


// Render listings
if (count($listings) > 0) {
    foreach($listings as $index => $listing) {
        get_template_part('template-parts/cards/suggestion-listing-card', '', [
            'post_id'                => $listing['post_id'],
            'event_id'               => $event_id,
            'name'                   => $listing['name'],
            'rating'                 => $listing['rating'],
            'review_count'           => $listing['review_count'],
            'location'               => $listing['city'] . ', ' . $listing['state'],
            'description'            => $listing['description'],
            'genres'                 => (!empty($listing['genre'])) ? array_map(fn($genre) => $genre->name, $listing['genre']) : [], //$genres,
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
    get_template_part( 'template-parts/global/no-results-content/no-suggestion-listings');
}
