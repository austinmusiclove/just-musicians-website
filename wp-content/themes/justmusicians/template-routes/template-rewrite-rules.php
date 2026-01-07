<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function template_route_rewrite_rules() {

    // Buyers
    add_rewrite_rule(
        '^buyers/([0-9]+)/?',
        'index.php?custom-template=buyers&buyer-id=$matches[1]',
        'top'
    );

}
add_action('init', 'template_route_rewrite_rules');

function register_template_route_query_vars($vars) {
    $vars[] = 'custom-template';
    $vars[] = 'buyer-id';
    return $vars;
}
add_filter('query_vars', 'register_template_route_query_vars');
