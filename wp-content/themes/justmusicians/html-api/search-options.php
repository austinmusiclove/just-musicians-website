<?php
$search_term = stripslashes($_GET['s'] ?? '');
$lat = !empty($_GET['lat']) ? stripslashes($_GET['lat']) : null;
$lng = !empty($_GET['lng']) ? stripslashes($_GET['lng']) : null;
if (empty($search_term)) {
    get_template_part('template-parts/search/search-state-1');
} else {
    $get_listings_args = [
        'name_search' => $search_term,
    ];
    if ($lat !== null && $lng !== null) {
        $get_listings_args['lat'] = $lat;
        $get_listings_args['lng'] = $lng;
    }
    $result = get_listings($get_listings_args);
    $listings = $result['listings'];
    $categories       = get_terms_decoded('mcategory', 'names', $search_term, true);
    $genres           = get_terms_decoded('genre', 'names', $search_term, true);
    $subgenres        = get_terms_decoded('subgenre', 'names', $search_term, true);
    $instrumentations = get_terms_decoded('instrumentation', 'names', $search_term, true);
    $settings         = get_terms_decoded('setting', 'names', $search_term, true);
    get_template_part('template-parts/search/search-state-2', '', array(
        'listings' => $listings,
        'categories' => $categories,
        'genres' => $genres,
        'subgenres' => $subgenres,
        'instrumentations' => $instrumentations,
        'settings' => $settings,
    ));
}
