<?php
$search_term = stripslashes($_GET['s']);
if (empty($search_term)) {
    get_template_part('template-parts/search/mobile-search-state-1', '', array());
} else {
    $result = get_listings([
        'name_search' => $search_term
    ]);
    $listings = $result['listings'];
    $categories       = get_terms_decoded('mcategory', 'names', $search_term);
    $genres           = get_terms_decoded('genre', 'names', $search_term);
    $subgenres        = get_terms_decoded('subgenre', 'names', $search_term);
    $instrumentations = get_terms_decoded('instrumentation', 'names', $search_term);
    $settings         = get_terms_decoded('setting', 'names', $search_term);
    get_template_part('template-parts/search/mobile-search-state-2', '', array(
        'listings' => $listings,
        'categories' => $categories,
        'genres' => $genres,
        'subgenres' => $subgenres,
        'instrumentations' => $instrumentations,
        'settings' => $settings,
    ));
}
