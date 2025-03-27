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
} else if ($next_page == 2) { ?>
    <p>No results</p>
<?php } else { ?>
    <p>No more results</p>
<?php }


// Render Filters
// TODO only swap out the filter section that was altered
$default_categories = array_values(array_diff([get_default_option('category', 3), get_default_option('category', 2), get_default_option('category', 1), get_default_option('category', 0)], $valid_categories));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'category-filters',
    'title' => 'Categories',
    'input_name' => 'categories', // should match the input name used for the tag check boxes
    'tag_1' => $valid_categories[0] ?? array_pop($default_categories),
    'tag_2' => $valid_categories[1] ?? array_pop($default_categories),
    'tag_3' => $valid_categories[2] ?? array_pop($default_categories),
    'tag_4' => $valid_categories[3] ?? array_pop($default_categories),
    'tag_1_selected' => !empty($valid_categories[0]),
    'tag_2_selected' => !empty($valid_categories[1]),
    'tag_3_selected' => !empty($valid_categories[2]),
    'tag_4_selected' => !empty($valid_categories[3]),
    'show_modal_var' => 'showCategoryModal'
));
$default_genres = array_values(array_diff([get_default_option('genre', 3), get_default_option('genre', 2), get_default_option('genre', 1), get_default_option('genre', 0)], $valid_genres));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'genre-filters',
    'title' => 'Genre',
    'input_name' => 'genres',
    'tag_1' => $valid_genres[0] ?? array_pop($default_genres),
    'tag_2' => $valid_genres[1] ?? array_pop($default_genres),
    'tag_3' => $valid_genres[2] ?? array_pop($default_genres),
    'tag_4' => $valid_genres[3] ?? array_pop($default_genres),
    'tag_1_selected' => !empty($valid_genres[0]),
    'tag_2_selected' => !empty($valid_genres[1]),
    'tag_3_selected' => !empty($valid_genres[2]),
    'tag_4_selected' => !empty($valid_genres[3]),
    'show_modal_var' => 'showGenreModal'
));
$default_subgenres = array_values(array_diff([get_default_option('subgenre', 3), get_default_option('subgenre', 2), get_default_option('subgenre', 1), get_default_option('subgenre', 0)], $valid_subgenres));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'subgenre-filters',
    'title' => 'Sub Genre',
    'input_name' => 'subgenres',
    'tag_1' => $valid_subgenres[0] ?? array_pop($default_subgenres),
    'tag_2' => $valid_subgenres[1] ?? array_pop($default_subgenres),
    'tag_3' => $valid_subgenres[2] ?? array_pop($default_subgenres),
    'tag_4' => $valid_subgenres[3] ?? array_pop($default_subgenres),
    'tag_1_selected' => !empty($valid_subgenres[0]),
    'tag_2_selected' => !empty($valid_subgenres[1]),
    'tag_3_selected' => !empty($valid_subgenres[2]),
    'tag_4_selected' => !empty($valid_subgenres[3]),
    'show_modal_var' => 'showSubGenreModal'
));
$default_instrumentations = array_values(array_diff([get_default_option('instrumentation', 3), get_default_option('instrumentation', 2), get_default_option('instrumentation', 1), get_default_option('instrumentation', 0)], $valid_instrumentations));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'instrumentation-filters',
    'title' => 'Instrumentation',
    'input_name' => 'instrumentations',
    'tag_1' => $valid_instrumentations[0] ?? array_pop($default_instrumentations),
    'tag_2' => $valid_instrumentations[1] ?? array_pop($default_instrumentations),
    'tag_3' => $valid_instrumentations[2] ?? array_pop($default_instrumentations),
    'tag_4' => $valid_instrumentations[3] ?? array_pop($default_instrumentations),
    'tag_1_selected' => !empty($valid_instrumentations[0]),
    'tag_2_selected' => !empty($valid_instrumentations[1]),
    'tag_3_selected' => !empty($valid_instrumentations[2]),
    'tag_4_selected' => !empty($valid_instrumentations[3]),
    'show_modal_var' => 'showInstrumentationModal'
));
$default_settings = array_values(array_diff([get_default_option('setting', 3), get_default_option('setting', 2), get_default_option('setting', 1), get_default_option('setting', 0)], $valid_settings));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'setting-filters',
    'title' => 'Settings',
    'input_name' => 'settings',
    'tag_1' => $valid_settings[0] ?? array_pop($default_settings),
    'tag_2' => $valid_settings[1] ?? array_pop($default_settings),
    'tag_3' => $valid_settings[2] ?? array_pop($default_settings),
    'tag_4' => $valid_settings[3] ?? array_pop($default_settings),
    'tag_1_selected' => !empty($valid_settings[0]),
    'tag_2_selected' => !empty($valid_settings[1]),
    'tag_3_selected' => !empty($valid_settings[2]),
    'tag_4_selected' => !empty($valid_settings[3]),
    'show_modal_var' => 'showSettingModal'
));
