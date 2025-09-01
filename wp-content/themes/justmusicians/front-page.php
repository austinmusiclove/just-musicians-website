<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JustMuscians
 */

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');
// Get user inquiries
$inquiries_result = get_user_inquiries([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$inquiries_map = array_column($inquiries_result['inquiries'], null, 'post_id');



get_header();

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => false,
    'inquiries_map'    => $inquiries_map,
    'collections_map'  => $collections_map,
    'qcategory'        => isset($_GET['qcategory'])        ? $_GET['qcategory']        : '',
    'qgenre'           => isset($_GET['qgenre'])           ? $_GET['qgenre']           : '',
    'qsubgenre'        => isset($_GET['qsubgenre'])        ? $_GET['qsubgenre']        : '',
    'qinstrumentation' => isset($_GET['qinstrumentation']) ? $_GET['qinstrumentation'] : '',
    'qsetting'         => isset($_GET['qsetting'])         ? $_GET['qsetting']         : '',
]);

get_footer();
