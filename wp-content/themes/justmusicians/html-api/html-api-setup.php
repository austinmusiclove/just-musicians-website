<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function html_api_rewrite_rules() {
    add_rewrite_rule(
        '^wp-html/v1/([^/]+)/?',  // The URL pattern to match (e.g., /special-url/)
        'index.php?wp-html-v1=$matches[1]&' . $_SERVER['QUERY_STRING'],  // The query variable that WordPress will use to trigger custom logic
        'top'
    );
}
add_action('init', 'html_api_rewrite_rules');

function register_html_api_query_vars($vars) {
    $vars[] = 'wp-html-v1';  // Register the query variable
    return $vars;
}
add_filter('query_vars', 'register_html_api_query_vars');


function html_api_v1_template_redirects() {
    $path = get_query_var('wp-html-v1');
    if ($path == 'listings') {
        include_once get_template_directory() . '/html-api/listings.php';
        exit;
    } else if ($path == 'search-options') {
        include_once get_template_directory() . '/html-api/search-options.php';
        exit;
    } else if ($path == 'search-options-mobile') {
        include_once get_template_directory() . '/html-api/search-options-mobile.php';
        exit;
    } else if ($path == 'register-user') {
        include_once get_template_directory() . '/html-api/register-user.php';
        exit;
    } else if ($path == 'activate-account') {
        include_once get_template_directory() . '/html-api/activate-account.php';
        exit;
    }
}
add_action('template_redirect', 'html_api_v1_template_redirects');

