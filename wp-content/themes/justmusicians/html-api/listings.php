<?php
// Get listings
$result = get_listings([
    'search' => $_GET['search'],
    'genres' => $_GET['genres'],
    'types' => $_GET['types'],
    'tags' => $_GET['tags'],
    'verified' => $_GET['verified'],
    'page' => $_GET['page'],
]);
$listings = $result['listings'];
$valid_genres = $result['valid_genres'];
$valid_tags = $result['valid_tags'];
$valid_types = $result['valid_types'];
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
            'facebook_url' => $listing['facebook_url'],
            'instagram_url' => $listing['instagram_url'],
            'x_url' => $listing['x_url'],
            'youtube_url' => $listing['youtube_url'],
            'tiktok_url' => $listing['tiktok_url'],
            'bandcamp_url' => $listing['bandcamp_url'],
            'spotify_artist_url' => $listing['spotify_artist_url'],
            'apple_music_artist_url' => $listing['apple_music_artist_url'],
            'soundcloud_url' => $listing['soundcloud_url'],
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
$default_genres = array_values(array_diff(['Country', 'Rock', 'Pop', 'Soul/RnB', 'Latin', 'Hip Hop/Rap', 'Folk'], $valid_genres));
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
$default_types = array_values(array_diff(['Sound Engineer', 'Producer', 'Artist', 'Musician', 'DJ', 'Band'], $valid_types));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'vt' => $valid_types,
    'dt' => $default_types,
    'id' => 'type-filters',
    'title' => 'Type',
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
$default_tags = array_values(array_diff(['Punk Band', 'Live Looper', 'Orchestral', 'Background Music', 'Wedding Band', 'Cover Band', 'Acoustic'], $valid_tags));
echo get_template_part('template-parts/filters/elements/tags', '', array(
    'id' => 'category-filters',
    'title' => 'Category',
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
