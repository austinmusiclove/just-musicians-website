<?php

// Get listings
$page          = $_GET['page']                  ?? 1;
$collection_id = get_query_var('collection-id') ?? 0;

// Check if user is authorized
$is_authorized = user_can_view_collection($collection_id);
if ( is_wp_error($is_authorized) ) {
    $message = 'Unauthorized: ' . $is_authorized->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Resolve the collection's listing ids server-side
if ($collection_id == 0) {
    $listing_ids = get_user_meta(get_current_user_id(), 'favorites', true);
} else {
    $listing_ids = get_post_meta($collection_id, 'listings', true);
}
$listing_ids = is_array($listing_ids) ? array_values(array_filter($listing_ids, 'is_numeric')) : [];

$result = get_listings_by_id([
    'listing_ids' => $listing_ids,
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
        get_template_part('template-parts/cards/standard-listing-card', '', [
            'post_id'                => $listing['post_id'],
            'name'                   => $listing['name'],
            'rating'                 => $listing['rating'],
            'review_count'           => $listing['review_count'],
            'city'                   => $listing['city'],
            'state'                  => $listing['state'],
            'zip_code'               => $listing['zip_code'],
            'location'               => $listing['city'] . ', ' . $listing['state'],
            'description'            => $listing['description'],
            'genres'                 => $genres,
            'thumbnail_url'          => $listing['thumbnail_url'],
            'phone'                  => isset($listing['phone']) ? $listing['phone'] : null,
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
            'youtube_video_data'     => $listing['youtube_video_data'],
            'verified'               => $listing['verified'],
            'permalink'              => $listing['permalink'],
            'lazyload_thumbnail'     => $index >= 3,
            'hx-request_path'        => 'collections/' . $collection_id . '/listings',
            'collection_id'          => $collection_id,
            'reorderable'            => true,
            'index'                  => $index,
            'last'                   => $index == array_key_last($listings),
            'is_last_page'           => $is_last_page,
            'next_page'              => $next_page,
        ]);
    }

} else if ($page == 1) {
    get_template_part( 'template-parts/global/empty-states/no-collection-listings');
}
