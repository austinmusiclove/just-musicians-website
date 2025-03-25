<?php
$search_term = stripslashes($_GET['s']);
if (empty($search_term)) {
    get_template_part('template-parts/search/search-state-1');
} else {
    $result = get_listings([
        'name_search' => $search_term
    ]);
    $listings = $result['listings'];
    $categories = get_terms(array(
        'taxonomy' => 'mcategory',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $genres = get_terms(array(
        'taxonomy' => 'genre',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $subgenres = get_terms(array(
        'taxonomy' => 'subgenre',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $instrumentations = get_terms(array(
        'taxonomy' => 'instrumentation',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $settings = get_terms(array(
        'taxonomy' => 'setting',
        'search' => $search_term,
        'fields' => 'names',
    ));
    $terms = [...$categories, ...$genres, ...$subgenres, ...$instrumentations, ...$settings];
    get_template_part('template-parts/search/search-state-2', '', array(
        'listings' => $listings,
        'terms' => $terms,
    ));
}
