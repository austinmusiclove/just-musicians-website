<?php
$search_term = stripslashes($_GET['s'] ?? '');
$lat = is_numeric($_GET['lat'] ?? '') ? (float) stripslashes($_GET['lat']) : null;
$lng = is_numeric($_GET['lng'] ?? '') ? (float) stripslashes($_GET['lng']) : null;
if (empty($search_term)) {
    get_template_part('template-parts/search/active-search/search-state-1');
} else {
    $listings = [];
    if ($lat !== null && $lng !== null) {
        $result = get_listings([
            'name_search' => $search_term,
            'lat'         => $lat,
            'lng'         => $lng,
        ]);
        $listings = !empty($result['listings']) ? $result['listings'] : [];
    }
    $categories       = get_terms_decoded('mcategory', 'names', $search_term, true);
    $genres           = get_terms_decoded('genre', 'names', $search_term, true);
    $subgenres        = get_terms_decoded('subgenre', 'names', $search_term, true);
    $instrumentations = get_terms_decoded('instrumentation', 'names', $search_term, true);
    $settings         = get_terms_decoded('setting', 'names', $search_term, true);
    get_template_part('template-parts/search/active-search/search-state-2', '', [
        'listings' => $listings,
        'categories' => $categories,
        'genres' => $genres,
        'subgenres' => $subgenres,
        'instrumentations' => $instrumentations,
        'settings' => $settings,
    ]);
}
