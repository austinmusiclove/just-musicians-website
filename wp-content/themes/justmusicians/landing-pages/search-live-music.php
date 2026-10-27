<?php

$breadcrumb_items = [
    [ 'label' => 'Home',       'url' => home_url('/') ],
    [ 'label' => 'Search', ],
];

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');



get_header();

if (
    ! isset($_GET['lat']) || $_GET['lat'] === '' ||
    ! isset($_GET['lng']) || $_GET['lng'] === '' ||
    ! isset($_GET['location_label']) || $_GET['location_label'] === ''
) {
    $location_label = 'Austin, Texas';
    $lat            = 30.2671500;
    $lng            = -97.7430600;
} else {
    $location_label = $_GET['location_label'];
    $lat            = floatval($_GET['lat']);
    $lng            = floatval($_GET['lng']);
}

get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => false,
    'hide_location_filter' => false,
    'hide_category_filter' => false,
    'hide_subgenre_filter' => false,
    'title'            => 'Find Live Musicians Near You',
    'collections_map'  => $collections_map,
    'breadcrumb_items' => $breadcrumb_items,
    'qcategory'        => isset($_GET['qcategory'])        ? $_GET['qcategory']        : '',
    'qgenre'           => isset($_GET['qgenre'])           ? $_GET['qgenre']           : '',
    'qsubgenre'        => isset($_GET['qsubgenre'])        ? $_GET['qsubgenre']        : '',
    'qinstrumentation' => isset($_GET['qinstrumentation']) ? $_GET['qinstrumentation'] : '',
    'qsetting'         => isset($_GET['qsetting'])         ? $_GET['qsetting']         : '',
    'location_label'   => $location_label,
    'lat'              => $lat,
    'lng'              => $lng,
]);

get_footer();
