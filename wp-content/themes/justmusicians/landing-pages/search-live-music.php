<?php

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');



get_header();

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => false,
    'collections_map'  => $collections_map,
    'title'            => "Find Live Musicians Near You",
    'qcategory'        => isset($_GET['qcategory'])        ? $_GET['qcategory']        : '',
    'qgenre'           => isset($_GET['qgenre'])           ? $_GET['qgenre']           : '',
    'qsubgenre'        => isset($_GET['qsubgenre'])        ? $_GET['qsubgenre']        : '',
    'qinstrumentation' => isset($_GET['qinstrumentation']) ? $_GET['qinstrumentation'] : '',
    'qsetting'         => isset($_GET['qsetting'])         ? $_GET['qsetting']         : '',
]);

get_footer();
