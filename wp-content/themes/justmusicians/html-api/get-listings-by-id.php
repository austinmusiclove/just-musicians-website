<?php

// Get listings
$page          = $_GET['page']          ?? 1;
$collection_id = $_GET['collection_id'] ?? 0;
$result = get_listings_by_id([
    'listing_ids' => !empty($_GET['listing_ids']) ? $_GET['listing_ids'] : [],
    'page'        => $page,
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
        $genres = [];
        $listing['genre'] ??= [];
        if (!empty($listing['genre'])) {
            $genres = array_map(fn($genre) => $genre->name, $listing['genre']);
        }
        get_template_part('template-parts/search/standard-listing', '', [
            'post_id'                => $listing['post_id'],
            'name'                   => $listing['name'],
            'location'               => $listing['city'] . ', ' . $listing['state'],
            'description'            => $listing['description'],
            'genres'                 => $genres,
            'thumbnail_url'          => $listing['thumbnail_url'],
            'website'                => $listing['website'],
            'facebook_url'           => $listing['facebook_url'],
            'instagram_url'          => $listing['instagram_url'],
            'x_url'                  => $listing['x_url'],
            'youtube_url'            => $listing['youtube_url'],
            'tiktok_url'             => $listing['tiktok_url'],
            'bandcamp_url'           => $listing['bandcamp_url'],
            'spotify_artist_url'     => $listing['spotify_artist_url'],
            'apple_music_artist_url' => $listing['apple_music_artist_url'],
            'soundcloud_url'         => $listing['soundcloud_url'],
            'youtube_video_urls'     => $listing['youtube_video_urls'],
            'youtube_video_ids'      => $listing['youtube_video_ids'],
            'verified'               => $listing['verified'],
            'lazyload_thumbnail'     => $index >= 3,
            'hx-request_path'        => 'listings-by-id',
            'allow_hide'             => true,
            'collection_id'          => $collection_id,
            'last'                   => $index == array_key_last($listings),
            'is_last_page'           => $is_last_page,
            'next_page'              => $next_page,
        ]);
    }
    echo '<span id="max_num_results" hx-swap-oob="outerHTML">' . $max_num_results . $results_label . '</span>';

} else if ($page == 1) {
    get_template_part( 'template-parts/content/no-collection-listings');
}
