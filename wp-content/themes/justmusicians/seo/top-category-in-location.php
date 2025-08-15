<?php

$category = get_query_var('seo-category') ?? 0;
$location = get_query_var('seo-location') ?? 0;

// Set page title and category filter
$title = '';
$category_name = '';
$term = get_term_by('slug', $category, 'mcategory');
if ( $term and !is_wp_error( $term ) ) {
    $category_name = $term->name;
    $title = 'Top ' . $category_name . 's in Austin, Texas';

// If the term doesn't exist or there was an error, just redirect to home page
} else {
    wp_redirect(site_url());
}



// Get user collections and inquiries
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');
$inquiries_result = get_user_inquiries([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$inquiries_map = array_column($inquiries_result['inquiries'], null, 'post_id');



// Generate page content
get_header();

echo get_template_part('template-parts/search/search-page', '', [
    'title'            => $title,
    'inquiries_map'    => $inquiries_map,
    'collections_map'  => $collections_map,
    'qcategory'        => $category_name,
    'qgenre'           => '',
    'qsubgenre'        => '',
    'qinstrumentation' => '',
    'qsetting'         => '',
]);

get_footer();
