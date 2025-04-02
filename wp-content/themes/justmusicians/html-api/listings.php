<?php
// Get listings
$page = $_GET['page'] ?: 1;
$result = get_listings([
    'search' => $_GET['search'],
    'categories' => $_GET['categories'],
    'genres' => $_GET['genres'],
    'subgenres' => $_GET['subgenres'],
    'instrumentations' => $_GET['instrumentations'],
    'settings' => $_GET['settings'],
    'tags' => $_GET['tags'],
    'verified' => $_GET['verified'],
    'page' => $page,
]);
$listings = $result['listings'];
$valid_categories = $result['valid_categories'];
$valid_genres = $result['valid_genres'];
$valid_subgenres = $result['valid_subgenres'];
$valid_instrumentations = $result['valid_instrumentations'];
$valid_settings = $result['valid_settings'];
$valid_tags = $result['valid_tags'];
$next_page = $result['next_page'];

// Render listings
if (count($listings) > 0) {
    foreach($listings as $index => $listing) {
        $genres = [];
        $listing['genre'] ??= [];
        if (!empty($listing['genre'])) {
            $genres = array_map(fn($genre) => $genre->name, $listing['genre']);
        }
        $result_id = $page . '-' . $index;
        get_template_part('template-parts/search/standard-listing', '', [
            'name' => $listing['name'],
            'location' => $listing['city'] . ', ' . $listing['state'],
            'description' => $listing['description'],
            'genres' => $genres,
            'thumbnail_url' => $listing['thumbnail_url'],
            'website' => $listing['website'],
            'facebook_url' => $listing['facebook_url'],
            'instagram_url' => $listing['instagram_url'],
            'x_url' => $listing['x_url'],
            'youtube_url' => $listing['youtube_url'],
            'tiktok_url' => $listing['tiktok_url'],
            'bandcamp_url' => $listing['bandcamp_url'],
            'spotify_artist_url' => $listing['spotify_artist_url'],
            'apple_music_artist_url' => $listing['apple_music_artist_url'],
            'soundcloud_url' => $listing['soundcloud_url'],
            'youtube_video_urls' => $listing['youtube_video_urls'],
            'youtube_video_ids' => $listing['youtube_video_ids'],
            'youtube_player_ids' => array_map(fn($video_id, $video_index) => $video_id . '-' . $result_id . '-' . $video_index, $listing['youtube_video_ids'], array_keys($listing['youtube_video_ids'])),
            'verified' => $listing['verified'],
            'lazyload_thumbnail' => $index >= 3,
            'last' => $index == array_key_last($listings),
            'next_page' => $next_page,
        ]);
    }

} else if ($page == 1) {
    get_template_part( 'template-parts/content/no-search-results');
} else {
    get_template_part( 'template-parts/content/no-more-results');
}




// Render Filters
// TODO only swap out the filter section that was altered
if ($page == 1) {
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'category-filters',
        'title' => 'Categories',
        'input_name' => 'categories', // should match the input name used for the tag check boxes
        'default_tags' => get_default_options('category'),
        'tags' => $valid_categories,
        'show_modal_var' => 'showCategoryModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'genre-filters',
        'title' => 'Genre',
        'input_name' => 'genres',
        'default_tags' => get_default_options('genre'),
        'tags' => $valid_genres,
        'show_modal_var' => 'showGenreModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'subgenre-filters',
        'title' => 'Sub Genre',
        'input_name' => 'subgenres',
        'default_tags' => get_default_options('subgenre'),
        'tags' => $valid_subgenres,
        'show_modal_var' => 'showSubGenreModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'instrumentation-filters',
        'title' => 'Instrumentation',
        'input_name' => 'instrumentations',
        'default_tags' => get_default_options('instrumentation'),
        'tags' => $valid_instrumentations,
        'show_modal_var' => 'showInstrumentationModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'setting-filters',
        'title' => 'Settings',
        'input_name' => 'settings',
        'default_tags' => get_default_options('setting'),
        'tags' => $valid_settings,
        'show_modal_var' => 'showSettingModal'
    ));
}
