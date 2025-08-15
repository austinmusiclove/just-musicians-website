<?php
function seo_pages_template_redirects() {
    if (!empty(get_query_var('seo-category')) and !empty(get_query_var('seo-location'))) {
        include_once get_template_directory() . '/seo/top-category-in-location.php'; exit;
    }
}
add_action('template_redirect', 'seo_pages_template_redirects');
