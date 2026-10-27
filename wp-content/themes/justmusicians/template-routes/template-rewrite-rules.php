<?php
// This file handles routing custom paths to templates to provide a library of GET APIs for web front end
function template_route_rewrite_rules() {

    // Buyers
    add_rewrite_rule(
        '^buyers/([0-9]+)/?',
        'index.php?custom-template=buyers&buyer-id=$matches[1]',
        'top'
    );

    // Pending listings
    add_rewrite_rule(
        '^listing-pid/([0-9]+)/?$',
        'index.php?custom-template=unpublished-listing&listing-id=$matches[1]',
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

    // Search page
    add_rewrite_rule(
        '^(live-music)/search/?$',
        'index.php?custom-template=landing-search&vertical=$matches[1]',
        'top'
    );

    // Locale Category Pages
    // Prevent this catch-all rule from swallowing single-post URLs such as /collection/country-bands/ or /artist/jazz-bands/.
    $categories = implode('|', [ 'country-bands', 'wedding-bands', 'live-bands', 'party-bands', 'funk-bands', 'cover-bands', 'soul-bands', 'tribute-bands', 'punk-bands', 'metal-bands', 'jam-bands', 'rappers', 'singer-songwriters', 'violinists', 'mariachi-bands', 'latin-bands', 'salsa-bands', 'tejano-bands', 'brass-bands', 'djs', 'wedding-djs', 'folk-singers', 'folk-bands', 'indie-bands', 'singing-guitarists', 'singing-pianists', 'pianists' ]);
    $locales = implode('|', [ 'austin', 'san-antonio' ]);
    add_rewrite_rule(
        '^(' . $locales . ')/(' . $categories . ')/?$',
        'index.php?custom-template=landing-locale-category&lm-locale=$matches[1]&lm-category=$matches[2]',
        'top'
    );

}
add_action('init', 'template_route_rewrite_rules');

function register_template_route_query_vars($vars) {
    $vars[] = 'custom-template';
    $vars[] = 'application-id';
    $vars[] = 'buyer-id';
    $vars[] = 'listing-id';
    $vars[] = 'vertical';
    $vars[] = 'lm-locale';
    $vars[] = 'lm-category';
    return $vars;
}
add_filter('query_vars', 'register_template_route_query_vars');
