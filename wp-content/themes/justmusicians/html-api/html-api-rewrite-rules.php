<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function html_api_rewrite_rules() {
    add_rewrite_rule(
        '^wp-html/v1/([^/]+)/?$',  // The URL pattern to match (e.g., /special-url/)
        'index.php?wp-html-v1=$matches[1]',  // The query variable that WordPress will use to trigger custom logic
        'bottom'
    );

    // Listings
    add_rewrite_rule(
        '^wp-html/v1/listings/?([0-9]+)?/?$',
        'index.php?wp-html-v1=listings&listing-id=$matches[1]',
        'top'
    );

    // Collections
    add_rewrite_rule(
        '^wp-html/v1/collections/([0-9]+)/?$',
        'index.php?wp-html-v1=collections&collection-id=$matches[1]',
        'bottom'
    );
    add_rewrite_rule(
        '^wp-html/v1/collections/([0-9]+)/listings/?$',
        'index.php?wp-html-v1=collection-listings&collection-id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/collections/([0-9]+)/listings/([0-9]+)/?$',
        'index.php?wp-html-v1=collection-listing&collection-id=$matches[1]&listing-id=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^collection/favorites/?$',
        'index.php?wp-html-v1=favorites',
        'top'
    );

    // Inquiries
    add_rewrite_rule(
        '^wp-html/v1/inquiries/([0-9]+)/?$',
        'index.php?wp-html-v1=inquiries&inquiry-id=$matches[1]',
        'bottom'
    );
    add_rewrite_rule(
        '^wp-html/v1/inquiries/([0-9]+)/listings/?$',
        'index.php?wp-html-v1=inquiry-listings&inquiry-id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/inquiries/([0-9]+)/suggestions/?$',
        'index.php?wp-html-v1=inquiry-suggestions&inquiry-id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^wp-html/v1/inquiries/([0-9]+)/listings/([0-9]+)/?$',
        'index.php?wp-html-v1=inquiry-listing&inquiry-id=$matches[1]&listing-id=$matches[2]',
        'top'
    );

}
add_action('init', 'html_api_rewrite_rules');

function register_html_api_query_vars($vars) {
    $vars[] = 'wp-html-v1';
    $vars[] = 'listing-id';
    $vars[] = 'collection-id';
    $vars[] = 'inquiry-id';
    $vars[] = 'conversation-id';
    return $vars;
}
add_filter('query_vars', 'register_html_api_query_vars');
