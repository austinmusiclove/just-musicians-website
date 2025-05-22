<?php
function html_api_v1_template_redirects() {
    switch (get_query_var('wp-html-v1')) {

        // Listings
        case 'listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-listings.php'; exit;
                case 'POST'  : include_once get_template_directory() . '/html-api/post-listing.php'; exit;
                case 'DELETE': include_once get_template_directory() . '/html-api/delete-listing.php'; exit;
            }

        // Collections
        case 'collections':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-collections.php'; exit;
                case 'POST'  : include_once get_template_directory() . '/html-api/create-collection.php'; exit;
                case 'DELETE': include_once get_template_directory() . '/html-api/delete-collection.php'; exit;
            }
        case 'collection-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : include_once get_template_directory() . '/html-api/add-listing-to-collection.php'; exit;
                case 'DELETE': include_once get_template_directory() . '/html-api/remove-listing-from-collection.php'; exit;
            }
        case 'collection-listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-collection-listings.php'; exit;
            }
        case 'favorites':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/single-collection.php'; exit;
            }

        // Inquiries
        case 'inquiries':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-inquiries.php'; exit;
                case 'POST'  : include_once get_template_directory() . '/html-api/create-inquiry.php'; exit;
            }
        case 'inquiry-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : include_once get_template_directory() . '/html-api/add-listing-to-inquiry.php'; exit;
            }
        case 'inquiry-listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-inquiry-listings.php'; exit;
            }
        case 'inquiry-suggestions':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-inquiry-suggestions.php'; exit;
            }
        case 'requests':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/get-requests.php'; exit;
            }

        // Active Search
        case 'search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/search-options.php'; exit;
            }
        case 'search-options-mobile':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : include_once get_template_directory() . '/html-api/search-options-mobile.php'; exit;
            }

        // Register User
        case 'register-user':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : include_once get_template_directory() . '/html-api/register-user.php'; exit;
            }
    }
}
add_action('template_redirect', 'html_api_v1_template_redirects');
