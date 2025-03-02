<?php
if (empty($_GET['s'])) {
    get_template_part('template-parts/search/mobile-search-state-1', '', array());
} else {
    $search_term = $_GET['s'];
    $result = get_listings([
        'name_search' => $search_term
    ]);
    $listings = $result['listings'];
    $genres = get_terms(array(
        'taxonomy' => 'genre',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $tags = get_terms(array(
        'taxonomy' => 'tag',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $categories = [...$genres, ...$tags];
    get_template_part('template-parts/search/mobile-search-state-2', '', array(
        'listings' => $listings,
        'categories' => $categories,
    ));
}
