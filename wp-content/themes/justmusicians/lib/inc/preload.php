<?php
// HTMX Preload depends on having the proper cache control headers

add_action('template_redirect', function () {
    if (is_admin() || 'GET' !== $_SERVER['REQUEST_METHOD']) return;

    // WordPress already sends no-store for logged in users, don't overwrite it
    if (is_user_logged_in()) return;

    if (is_front_page() || is_page()) {
        header('Cache-Control: private, max-age=6', true);
    }
});
