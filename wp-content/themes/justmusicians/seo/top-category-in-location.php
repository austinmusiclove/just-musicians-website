<?php

$category = get_query_var('seo-category') ?? 0;
$location = get_query_var('seo-location') ?? 0;

// Set page title
$title = '';
switch ($category) {
    case 'rock-bands'    : $title = 'Top Rock Bands in Austin, Texas';    break;
    case 'country-bands' : $title = 'Top Country Bands in Austin, Texas'; break;
    case 'cover-bands'   : $title = 'Top Cover Bands in Austin, Texas';   break;
}

// Set filters
$qcategory = '';
switch ($category) {
    case 'rock-bands'    : $qcategory = 'Rock Band';    break;
    case 'country-bands' : $qcategory = 'Country Band'; break;
    case 'cover-bands'   : $qcategory = 'Cover Band';   break;
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
    'qcategory'        => $qcategory,
    'qgenre'           => '',
    'qsubgenre'        => '',
    'qinstrumentation' => '',
    'qsetting'         => '',
]);

get_footer();
