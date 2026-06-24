<?php
function html_api_v1_template_redirects() {
    global $wp_query;
    switch (get_query_var('wp-html-v1')) {

        // Listings
        case 'listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-listings.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/post-listing.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/delete-listing.php'; exit;
            }

        // Collections
        case 'collections':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-collections.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/create-collection.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/delete-collection.php'; exit;
            }
        case 'collection-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/add-listing-to-collection.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/remove-listing-from-collection.php'; exit;
            }
        case 'collection-listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-collection-listings.php'; exit;
            }
        case 'favorites':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/single-collection.php'; exit;
            }

        // Inquiries
        case 'inquiries':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-inquiries.php'; exit;
                case 'POST'  :
                    if (get_query_var('inquiry-id')) { status_header(200); include_once get_template_directory() . '/html-api/update-inquiry.php'; exit; }
                    else                             { status_header(200); include_once get_template_directory() . '/html-api/create-inquiry.php'; exit; }
            }
        case 'inquiry-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/add-listing-to-inquiry.php'; exit;
            }

        // Events and Proposals
        case 'events':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/create-event.php'; exit;
            }
        case 'update-event':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/update-event.php'; exit;
            }
        case 'event-applicants':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/event-applicants.php'; exit;
            }
        case 'request-proposal':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/request-proposal.php'; exit;
            }
        case 'respond-to-inquiry':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/respond-to-inquiry.php'; exit;
            }
        case 'my-events':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/get-my-events.php'; exit;
            }
        case 'my-gigs':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/get-my-gigs.php'; exit;
            }
        case 'inquiry-suggestions':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-inquiry-suggestions.php'; exit;
            }

        // Reviews
        case 'reviews':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/get-reviews.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/create-review.php'; exit;
            }

        // Active Search
        case 'search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/search-options.php'; exit;
            }
        case 'search-options-mobile':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/search-options-mobile.php'; exit;
            }
        case 'venue-search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/venue-search-options.php'; exit;
            }
        case 'location-search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/location-search-options.php'; exit;
            }
        case 'location-search-options-pc':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/location-search-options-pc.php'; exit;
            }

        // Messages
        case 'send-message-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/send-message-listing.php'; exit;
            }

        // Register User
        case 'register-user':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/register-user.php'; exit;
            }

        // Account Settings
        case 'account-settings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/post-account-settings.php'; exit;
            }

        // Compensation Reports
        case 'compensation-reports':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/post-compensation-report.php'; exit;
            }

        // Notifications
        case 'clear-notification':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/clear-notification.php'; exit;
            }
    }
}
add_action('template_redirect', 'html_api_v1_template_redirects');
