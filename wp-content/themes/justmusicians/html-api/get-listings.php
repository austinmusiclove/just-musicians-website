<?php

// Get listings
$page = $_GET['page'] ?? 1;
$result = get_listings([
    'search'            => !empty($_GET['search']) ? $_GET['search'] : '',
    'categories'        => !empty($_GET['categories']) ? $_GET['categories'] : [],
    'genres'            => !empty($_GET['genres']) ? $_GET['genres'] : [],
    'subgenres'         => !empty($_GET['subgenres']) ? $_GET['subgenres'] : [],
    'instrumentations'  => !empty($_GET['instrumentations']) ? $_GET['instrumentations'] : [],
    'settings'          => !empty($_GET['settings']) ? $_GET['settings'] : [],
    'verified'          => !empty($_GET['verified']) ? $_GET['verified'] : null,
    'min_ensemble_size' => !empty($_GET['min_ensemble_size']) ? $_GET['min_ensemble_size'] : null,
    'max_ensemble_size' => !empty($_GET['max_ensemble_size']) ? $_GET['max_ensemble_size'] : null,
    'page'              => $page,
]);
$listings               = $result['listings'];
$valid_categories       = $result['valid_categories'];
$valid_genres           = $result['valid_genres'];
$valid_subgenres        = $result['valid_subgenres'];
$valid_instrumentations = $result['valid_instrumentations'];
$valid_settings         = $result['valid_settings'];
$min_ensemble_size      = $result['min_ensemble_size'];
$max_ensemble_size      = $result['max_ensemble_size'];
$max_num_results        = $result['max_num_results'];
$max_num_pages          = $result['max_num_pages'];
$is_last_page           = $page == $max_num_pages;
$next_page              = $result['next_page'];

// Render listings
if (count($listings) > 0) {
    foreach($listings as $index => $listing) {
        $genres = [];
        $listing['genre'] ??= [];
        if (!empty($listing['genre'])) {
            $genres = array_map(fn($genre) => $genre->name, $listing['genre']);
        }
        get_template_part('template-parts/listings/standard-listing', '', [
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
    get_template_part( 'template-parts/content/no-search-results');
}
if ($is_last_page) {
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
    echo get_template_part('template-parts/filters/elements/ensemble-size-input', '', [
        'min_value'         => $min_ensemble_size ? $min_ensemble_size : 1,
        'max_value'         => $max_ensemble_size ? $max_ensemble_size : 10,
        'min_input_name'    => 'min_ensemble_size',
        'max_input_name'    => 'max_ensemble_size',
        'min_input_x_model' => 'minEnsembleSize',
        'max_input_x_model' => 'maxEnsembleSize',
        'min_input_x_ref'   => 'minEnsembleSize',
        'max_input_x_ref'   => 'maxEnsembleSize',
        'on_change_event'   => 'filterupdate',
    ]);
}

// Render total resutls count
?><span id="max_num_results" hx-swap-oob="outerHTML"><?php
    echo $max_num_results;
    if ($max_num_results == 1) { echo ' result'; } else { echo ' results'; }?>
</span><?php


