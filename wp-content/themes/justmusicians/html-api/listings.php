<?php
// Get listings
$result = get_listings([
    'search' => $_GET['search'],
    'types' => $_GET['types'],
    'genres' => $_GET['genres'],
    'subgenres' => $_GET['subgenres'],
    'instrumentations' => $_GET['instrumentations'],
    'settings' => $_GET['settings'],
    'tags' => $_GET['tags'],
    'verified' => $_GET['verified'],
    'page' => $_GET['page'],
]);
$listings = $result['listings'];
$valid_types = $result['valid_types'];
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
        get_template_part('template-parts/search/profile', '', [
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
$default_types = array_values(array_diff([get_default_option('type', 3), get_default_option('type', 2), get_default_option('type', 1), get_default_option('type', 0)], $valid_types));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'type-filters',
    'title' => 'Listing Type',
    'input_name' => 'types', // should match the input name used for the tag check boxes
    'tag_1' => $valid_types[0] ?? array_pop($default_types),
    'tag_2' => $valid_types[1] ?? array_pop($default_types),
    'tag_3' => $valid_types[2] ?? array_pop($default_types),
    'tag_4' => $valid_types[3] ?? array_pop($default_types),
    'tag_1_selected' => !empty($valid_types[0]),
    'tag_2_selected' => !empty($valid_types[1]),
    'tag_3_selected' => !empty($valid_types[2]),
    'tag_4_selected' => !empty($valid_types[3]),
    'alpine_modal_var' => 'showTypeModal'
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
    'alpine_modal_var' => 'showGenreModal'
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
    'alpine_modal_var' => 'showSubGenreModal'
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
    'alpine_modal_var' => 'showInstrumentationModal'
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
    'alpine_modal_var' => 'showSettingModal'
));
$default_tags = array_values(array_diff(['Punk Band', 'Live Looper', 'Orchestral', 'Background Music', 'Wedding Band', 'Cover Band', 'Acoustic'], $valid_tags));
$default_tags = array_values(array_diff([get_default_option('tag', 3), get_default_option('tag', 2), get_default_option('tag', 1), get_default_option('tag', 0)], $valid_tags));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'tag-filters',
    'title' => 'Other Categories',
    'input_name' => 'tags',
    'tag_1' => $valid_tags[0] ?? array_pop($default_tags),
    'tag_2' => $valid_tags[1] ?? array_pop($default_tags),
    'tag_3' => $valid_tags[2] ?? array_pop($default_tags),
    'tag_4' => $valid_tags[3] ?? array_pop($default_tags),
    'tag_1_selected' => !empty($valid_tags[0]),
    'tag_2_selected' => !empty($valid_tags[1]),
    'tag_3_selected' => !empty($valid_tags[2]),
    'tag_4_selected' => !empty($valid_tags[3]),
    'alpine_modal_var' => 'showTagModal'
));
