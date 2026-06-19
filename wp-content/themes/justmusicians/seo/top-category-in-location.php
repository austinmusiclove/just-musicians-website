<?php

$category = get_query_var('seo-category') ?? 0;
$location = get_query_var('seo-location') ?? 0;

$location_map = [
    'austin-tx' => ['city' => 'Austin', 'state' => 'Texas', 'lat' => 30.2672, 'lng' => -97.7431],
];

$location_label = '';
$city_lat = null;
$city_lng = null;

if (!empty($location) && isset($location_map[$location])) {
    $loc = $location_map[$location];
    $location_label = $loc['city'] . ', ' . $loc['state'];
    $city_lat = $loc['lat'];
    $city_lng = $loc['lng'];
} else {
    wp_redirect(site_url());
    exit;
}

// Set page title and category filter
$title = '';
$category_name = '';
$term = get_term_by('slug', $category, 'mcategory');
if ( $term and !is_wp_error( $term ) ) {
    $category_name = $term->name;
    $title = $category_name . 's in ' . $location_label;

// If the term doesn't exist or there was an error, just redirect to home page
} else {
    wp_redirect(site_url());
    exit;
}



// Get user collections and events
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');
$events_result = get_user_events([
    'nopaging'         => true,
    'start_date_after' => date('Y-m-d'),
]);
$events_map = array_column($events_result['events'], null, 'post_id');


// Generate page content
get_header( null, [
    'header_arg_location_label' => $location_label,
    'header_arg_lat'            => $city_lat,
    'header_arg_lng'            => $city_lng,
] );

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => true,
    'title'            => $title,
    'events_map'       => $events_map,
    'collections_map'  => $collections_map,
    'qcategory'        => $category_name,
    'qgenre'           => '',
    'qsubgenre'        => '',
    'qinstrumentation' => '',
    'qsetting'         => '',
    'lat'              => $city_lat,
    'lng'              => $city_lng,
]);

get_footer();
