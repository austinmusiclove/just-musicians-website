<?php

$category = get_query_var('seo-category') ?? 0;
$location = get_query_var('seo-location') ?? 0;

// Parse location
$location_label = '';
$city_lat = null;
$city_lng = null;
$location_data = !empty($location) ? get_seo_location($location) : null;
if (!empty($location_data)) {
    $location_label = $location_data['city'] . ', ' . $location_data['state'];
    $city_lat = $location_data['lat'];
    $city_lng = $location_data['lng'];
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
    $title       = get_seo_page_title( $category_name, $location_label );
    $description = get_seo_meta_description($category_name, $location_label);
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


// Generate page content
get_header( null, [
    'header_arg_location_label' => $location_label,
    'header_arg_lat'            => $city_lat,
    'header_arg_lng'            => $city_lng,
] );

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => true,
    'title'            => $title,
    'description'      => $description,
    'collections_map'  => $collections_map,
    'qcategory'        => $category_name,
    'qgenre'           => '',
    'qsubgenre'        => '',
    'qinstrumentation' => '',
    'qsetting'         => '',
    'lat'              => $city_lat,
    'lng'              => $city_lng,
    'area_served'      => [
        'city'  => $location_data['city'],
        'state' => $location_data['state'],
        'lat'   => $city_lat,
        'lng'   => $city_lng,
    ],
]);

get_footer();
