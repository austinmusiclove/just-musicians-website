<?php

add_filter('template_include', function($template) {

    switch (get_query_var('custom-template')) {

        // Buyers
        case 'buyers':
            $new_template = locate_template(['single-buyer.php']);
            if (!empty($new_template)) { return $new_template; }

        // Applications
        case 'musician-application':
            $new_template = locate_template(['musician-application.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'musician-application-embed':
            $new_template = locate_template(['musician-application-embed.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'musician-application-demo':
            $new_template = locate_template(['musician-application-demo.php']);
            if (!empty($new_template)) { return $new_template; }

        // Landing Pages
        case 'landing-search':
            $new_template = locate_template(['landing-pages/search-live-music.php']);
            if (!empty($new_template)) { return $new_template; }
        // Listings
        case 'unpublished-listing':
            if (!is_singular('listing')) { return $template; }
            $new_template = locate_template(['single-listing.php']);
            if (!empty($new_template)) { return $new_template; }

        case 'landing-locale-category':
            $new_template = locate_template(['landing-pages/locale-category.php']);
            if (!empty($new_template)) { return $new_template; }

        default:
            return $template;

    }
});

// Allow the application embed route to be framed by any site.
add_action('wp', function() {
    if (get_query_var('custom-template') === 'musician-application-embed') {
        add_filter('send_frame_options_header', '__return_false');
        add_filter('wp_headers', function($headers) {
            unset($headers['X-Frame-Options']);
            unset($headers['Content-Security-Policy']);
            return $headers;
        });
    }
}, 1);

// Load post for unpublished listing route
add_action('parse_request', function($wp) {
    if (is_admin() || !isset($wp->query_vars['custom-template']) || 'unpublished-listing' !== $wp->query_vars['custom-template']) { return; }

    $listing_id = (int) ($wp->query_vars['listing-id'] ?? 0);

    // The rewrite rule only produced these two vars, so replacing them leaves no stray
    // name/pagename/page/author vars that could make WP_Query match something else.
    $wp->query_vars = [
        'custom-template' => 'unpublished-listing',
        'listing-id'      => $listing_id,
        'post_type'       => 'listing',
        'p'               => $listing_id,
        'post_status'     => ['publish', 'pending'],
    ];
});

