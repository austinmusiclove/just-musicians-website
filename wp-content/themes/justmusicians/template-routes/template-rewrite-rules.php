<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function template_route_rewrite_rules() {

    // Buyers
    add_rewrite_rule(
        '^buyers/([0-9]+)/?',
        'index.php?custom-template=buyers&buyer-id=$matches[1]',
        'top'
    );

    // Applications
    add_rewrite_rule(
        '^musician-application/([0-9]+)/?$',
        'index.php?custom-template=musician-application&application-id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^musician-application/([0-9]+)/embed/?$',
        'index.php?custom-template=musician-application-embed&application-id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^musician-application/([0-9]+)/demo/?$',
        'index.php?custom-template=musician-application-demo&application-id=$matches[1]',
        'top'
    );

    // Landing Pages
    // Vertical
    add_rewrite_rule(
        '^(live-music)/?$',
        'index.php?custom-template=landing-vertical&vertical=$matches[1]',
        'top'
    );

    // Vertical search
    add_rewrite_rule(
        '^(live-music)/search/?$',
        'index.php?custom-template=landing-search&vertical=$matches[1]',
        'top'
    );

    // Locations
    add_rewrite_rule(
        '^(live-music)/locations/?$',
        'index.php?custom-template=landing-locations&vertical=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^(live-music)/locations/([^/]+)/?$',
        'index.php?custom-template=landing-region&vertical=$matches[1]&region=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^(live-music)/locations/([^/]+)/([^/]+)/?$',
        'index.php?custom-template=landing-locale&vertical=$matches[1]&region=$matches[2]&locale=$matches[3]',
        'top'
    );
    add_rewrite_rule(
        '^(live-music)/locations/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?custom-template=landing-locale-category&vertical=$matches[1]&region=$matches[2]&locale=$matches[3]&mcategory=$matches[4]',
        'top'
    );

    // Categories
    add_rewrite_rule(
        '^(live-music)/categories/?$',
        'index.php?custom-template=landing-categories&vertical=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^(live-music)/categories/([^/]+)/?$',
        'index.php?custom-template=landing-category&vertical=$matches[1]&mcategory=$matches[2]',
        'top'
    );

}
add_action('init', 'template_route_rewrite_rules');

function register_template_route_query_vars($vars) {
    $vars[] = 'custom-template';
    $vars[] = 'application-id';
    $vars[] = 'buyer-id';
    $vars[] = 'vertical';
    $vars[] = 'region';
    $vars[] = 'locale';
    $vars[] = 'mcategory';
    return $vars;
}
add_filter('query_vars', 'register_template_route_query_vars');
