<?php

function seo_pages_rewrite_rules() {
    add_rewrite_rule(
        '^live-music/([^/]+)/([^/]+)/?$',
        'index.php?seo-location=$matches[1]&seo-category=$matches[2]',
        'bottom'
    );
}
add_action('init', 'seo_pages_rewrite_rules');

function register_seo_pages_query_vars($vars) {
    $vars[] = 'seo-category';
    $vars[] = 'seo-location';
    return $vars;
}
add_filter('query_vars', 'register_seo_pages_query_vars');
