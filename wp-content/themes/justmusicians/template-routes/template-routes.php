<?php

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
        case 'landing-vertical':
            $new_template = locate_template(['landing-pages/vertical-live-music.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-search':
            $new_template = locate_template(['landing-pages/search-live-music.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-locations':
            $new_template = locate_template(['landing-pages/locations.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-region':
            $new_template = locate_template(['landing-pages/region.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-locale':
            $new_template = locate_template(['landing-pages/locale.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-locale-category':
            $new_template = locate_template(['landing-pages/locale-category.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-categories':
            $new_template = locate_template(['landing-pages/categories.php']);
            if (!empty($new_template)) { return $new_template; }
        case 'landing-category':
            $new_template = locate_template(['landing-pages/category.php']);
            if (!empty($new_template)) { return $new_template; }

        default:
            return $template;

    }
});
