<?php

add_filter('template_include', function($template) {

    switch (get_query_var('custom-template')) {

        // Buyers
        case 'buyers':
            $new_template = locate_template(['single-buyer.php']);
            if (!empty($new_template)) {
                return $new_template;
            }

        // Applications
        case 'musician-application':
            $new_template = locate_template(['musician-application.php']);
            if (!empty($new_template)) {
                return $new_template;
            }

        default:
            return $template;

    }
});
