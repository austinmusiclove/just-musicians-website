<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function html_api_rewrite_rules() {
    add_rewrite_rule(
        '^wp-html/v1/([^/]+)/?',  // The URL pattern to match (e.g., /special-url/)
        'index.php?wp-html-v1=$matches[1]&' . $_SERVER['QUERY_STRING'],  // The query variable that WordPress will use to trigger custom logic
        'bottom'
    );
    add_rewrite_rule(
        '^wp-html/v1/listings/?([0-9]+)?/?$',
        'index.php?wp-html-v1=listings&listing-id=$matches[1]&' . $_SERVER['QUERY_STRING'],
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/listings-by-id$',
        'index.php?wp-html-v1=listings-by-id&' . $_SERVER['QUERY_STRING'],
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/collections(?:/([0-9]+))?/?$',
        'index.php?wp-html-v1=collections&collection-id=$matches[1]&' . $_SERVER['QUERY_STRING'],
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/collections/([0-9]+)/listings/([0-9]+)/?$',
        'index.php?wp-html-v1=collections&collection-id=$matches[1]&listing-id=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^collection/favorites/?$',
        'index.php?wp-html-v1=favorites',
        'top'
    );
}
add_action('init', 'html_api_rewrite_rules');

function register_html_api_query_vars($vars) {
    $vars[] = 'wp-html-v1';
    $vars[] = 'listing-id';
    $vars[] = 'collection-id';
    return $vars;
}
add_filter('query_vars', 'register_html_api_query_vars');


function html_api_v1_template_redirects() {
    $path = get_query_var('wp-html-v1');
    $listing_id = get_query_var('listing-id');
    $collection_id = get_query_var('collection-id');


    // Listings
    if ($path == 'listings') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include_once get_template_directory() . '/html-api/get-listings.php'; exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            include_once get_template_directory() . '/html-api/post-listing.php'; exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            include_once get_template_directory() . '/html-api/delete-listing.php'; exit;
        }
    } else if ($path == 'listings-by-id') {
        include_once get_template_directory() . '/html-api/get-listings-by-id.php'; exit;


    // Collections
    } else if ($path == 'collections') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include_once get_template_directory() . '/html-api/get-collections.php'; exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($collection_id) or $collection_id == '0') {
                include_once get_template_directory() . '/html-api/add-listing-to-collection.php'; exit;
            } else {
                include_once get_template_directory() . '/html-api/create-collection.php'; exit;
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            if (!empty($listing_id)) {
                include_once get_template_directory() . '/html-api/remove-listing-from-collection.php'; exit;
            } else {
                include_once get_template_directory() . '/html-api/delete-collection.php'; exit;
            }
        }
    } else if ($path == 'favorites') {
        include_once get_template_directory() . '/single-collection.php'; exit;


    // Active Search
    } else if ($path == 'search-options') {
        include_once get_template_directory() . '/html-api/search-options.php'; exit;
    } else if ($path == 'search-options-mobile') {
        include_once get_template_directory() . '/html-api/search-options-mobile.php'; exit;

    // Register User
    } else if ($path == 'register-user') {
        include_once get_template_directory() . '/html-api/register-user.php'; exit;
    }
}
add_action('template_redirect', 'html_api_v1_template_redirects');

