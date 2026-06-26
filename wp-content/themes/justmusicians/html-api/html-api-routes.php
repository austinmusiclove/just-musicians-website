<?php
function html_api_v1_template_redirects() {
    global $wp_query;
    switch (get_query_var('wp-html-v1')) {

        // Listings
        case 'listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/listings/get-listings.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/listings/post-listing.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/listings/delete-listing.php'; exit;
            }

        // Collections
        case 'collections':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/collections/get-collections.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/collections/create-collection.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/collections/delete-collection.php'; exit;
            }
        case 'collection-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/collections/add-listing-to-collection.php'; exit;
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/collections/remove-listing-from-collection.php'; exit;
            }
        case 'collection-listings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/collections/get-collection-listings.php'; exit;
            }
        case 'favorites':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/single-collection.php'; exit;
            }

        // Events and Proposals
        case 'events':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  :
                    if (get_query_var('event-id')) { status_header(200); include_once get_template_directory() . '/html-api/events/update-event.php'; exit; }
                    else                           { status_header(200); include_once get_template_directory() . '/html-api/events/create-event.php'; exit; }
                case 'DELETE': status_header(200); include_once get_template_directory() . '/html-api/events/delete-event.php'; exit;
            }
        case 'event-applicants':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/events/get-event-applicants.php'; exit;
            }
        case 'request-proposal':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/events/request-proposal.php'; exit;
            }
        case 'respond-to-inquiry':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/events/respond-to-inquiry.php'; exit;
            }
        case 'my-events':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/events/get-my-events.php'; exit;
            }
        case 'my-gigs':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'  : status_header(200); include_once get_template_directory() . '/html-api/events/get-my-gigs.php'; exit;
            }
        case 'inquiry-suggestions':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/events/get-inquiry-suggestions.php'; exit;
            }

        // Reviews
        case 'reviews':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/reviews/get-reviews.php'; exit;
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/reviews/create-review.php'; exit;
            }

        // Active Search
        case 'search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/active-search/search-options.php'; exit;
            }
        case 'search-options-mobile':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/active-search/search-options-mobile.php'; exit;
            }
        case 'venue-search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/active-search/venue-search-options.php'; exit;
            }
        case 'location-search-options':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/active-search/location-search-options.php'; exit;
            }
        case 'location-search-options-pc':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET'   : status_header(200); include_once get_template_directory() . '/html-api/active-search/location-search-options-pc.php'; exit;
            }

        // Messages
        case 'send-message-listing':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/messages/send-message-listing.php'; exit;
            }

        // Compensation Reports
        case 'compensation-reports':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/comp-reports/post-compensation-report.php'; exit;
            }

        // Users
        case 'register-user':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/users/register-user.php'; exit;
            }
        case 'account-settings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/users/post-account-settings.php'; exit;
            }
        case 'clear-notification':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST'  : status_header(200); include_once get_template_directory() . '/html-api/users/clear-notification.php'; exit;
            }
    }
}
add_action('template_redirect', 'html_api_v1_template_redirects');
