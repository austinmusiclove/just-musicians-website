<?php
/**
 * JustMusicians functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JustMusicians
 */

if ( ! function_exists( 'hmm_setup' ) ) :
	function hmm_setup() {
		load_theme_textdomain( 'JustMusicians', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
        add_image_size('full-screen', 2000, '', true); // Large Thumbnail
        add_image_size('extra-large', 1500, '', true); // Large Thumbnail
        add_image_size('large', 1200, '', true); // Large Thumbnail
	    add_image_size('medium', 900, '', true); // Medium Thumbnail
        add_image_size('medium-small', 600, '', true); // Medium Small Thumbnail
	    add_image_size('small', 300, '', true); // Small Thumbnail
	    add_image_size('standard-listing', 400, 300, false); // Standard listing
	    add_image_size('tiny', 23, 23, true); // Standard listing
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'hmm_setup' );


// Enqueue Scripts and Styles
function hmm_scripts() {
    $pkg = json_decode(file_get_contents('package.json', true));
    global $post;

    // Parse request uri
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Alpine
    $alpine_dependencies = [];

    // Home and search pages
    if (is_front_page() or get_query_var('custom-template') === 'landing-locale-category' or preg_match('#^/live-music/search#', $path)) {

        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // Listing Form
        wp_enqueue_script('clear-listing-form-js', get_template_directory_uri() . '/lib/js/clear-listing-form.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    }

    // Archive
    if (is_archive()) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    }

    // Article and Page
    if ( is_singular(['post']) or is_page() or str_starts_with($_SERVER['REQUEST_URI'], '/blog') ) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    }

    // Venue Browsing page
    if (preg_match('#^/venues/?$#', $path)) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // Map
        wp_enqueue_script('maplibre-gl.5.14.0-js', get_template_directory_uri() . '/lib/js/maplibre-gl.5.14.0.js', [], $pkg->version, true);
        wp_enqueue_style( 'maplibre-gl.5.14.0-css', get_template_directory_uri() . '/lib/css/maplibre-gl.5.14.0.css', [], $pkg->version);
        wp_enqueue_script('maplibre-js', get_template_directory_uri() . '/lib/js/maplibre.js', [], $pkg->version, true);
        wp_localize_script('maplibre-js', 'siteData', [ 'siteUrl' => site_url() ]);

    // Single Venue Pages
    } elseif (preg_match('#^/venues/[^/]+/?$#', $path)) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // Reviews
        wp_enqueue_script('review-modal-js', get_template_directory_uri() . '/lib/js/review-modal.js', [], $pkg->version, []);

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'account-cropper-scripts-js' ], $pkg->version, []);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('account-cropper-scripts-js', get_template_directory_uri() . '/lib/js/account-cropper-scripts.js', [], $pkg->version, []);
        wp_localize_script('account-cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';

        // Chart.js
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', [], $pkg->version, true);
        wp_enqueue_script('bar-chart-js', get_template_directory_uri() . '/lib/js/charts/bar-chart.js', ['chart-js'], $pkg->version, true);
    }

    // Listing form
    if (str_starts_with($_SERVER['REQUEST_URI'], '/listing-form')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Input component scripts
        wp_enqueue_script('tag-input-scripts-js', get_template_directory_uri() . '/lib/js/tag-input-scripts.js', [], $pkg->version, []);
        wp_enqueue_script('youtube-urls-input-scripts-js', get_template_directory_uri() . '/lib/js/youtube-urls-input-scripts.js', [], $pkg->version, []);

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'cropper-scripts-js' ], $pkg->version, []);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('cropper-scripts-js', get_template_directory_uri() . '/lib/js/cropper-scripts.js', [], $pkg->version, []);
        wp_localize_script('cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';

        // Listing Form Image Upload handling
        wp_enqueue_script('listing-form-img-scripts', get_template_directory_uri() . '/lib/js/listing-form-img-scripts.js', [], $pkg->version, []);
    }

    // Musician Application form
    if (str_starts_with($_SERVER['REQUEST_URI'], '/musician-application')) {

        // Input component scripts
        wp_enqueue_script('tag-input-scripts-js', get_template_directory_uri() . '/lib/js/tag-input-scripts.js', [], $pkg->version, []);
        wp_enqueue_script('youtube-urls-input-scripts-js', get_template_directory_uri() . '/lib/js/youtube-urls-input-scripts.js', [], $pkg->version, []);

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'cropper-scripts-js' ], $pkg->version, []);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('cropper-scripts-js', get_template_directory_uri() . '/lib/js/cropper-scripts.js', [], $pkg->version, []);
        wp_localize_script('cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';

        // Listing Form Image Upload handling
        wp_enqueue_script('listing-form-img-scripts', get_template_directory_uri() . '/lib/js/listing-form-img-scripts.js', [], $pkg->version, []);
    }

    // Collection pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/collection/')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    }

    // Listing pages
    if (is_singular('listing')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // Reviews
        wp_enqueue_script('review-modal-js', get_template_directory_uri() . '/lib/js/review-modal.js', [], $pkg->version, [ 'strategy' => 'defer' ]);

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'account-cropper-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('account-cropper-scripts-js', get_template_directory_uri() . '/lib/js/account-cropper-scripts.js', [], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('account-cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';
    }

    // Single Event Pages
    if (is_singular('event')) {
        // Events
        wp_enqueue_script('events-js', get_template_directory_uri() . '/lib/js/events.js', [], $pkg->version, []);

        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    }

    // Single Application Pages
    if (is_singular('application')) {

        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // WYSIWYG Editor
        wp_enqueue_script('wysiwyg-js', get_template_directory_uri() . '/lib/js/wysiwyg.js', [], $pkg->version, []);

        // Export to download
        wp_enqueue_script('export-download-js', get_template_directory_uri() . '/lib/js/export-download.js', [], $pkg->version, [ 'strategy' => 'defer' ]);
    }

    // Create Application Form
    if (str_starts_with($_SERVER['REQUEST_URI'], '/application-form/')) {

        // WYSIWYG Editor
        wp_enqueue_script('wysiwyg-js', get_template_directory_uri() . '/lib/js/wysiwyg.js', [], $pkg->version, []);
    }

    // Messages pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/messages/')) {

        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', [ 'media-slider-js'], $pkg->version, [ 'strategy' => 'async' ]);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [ 'youtube-iframe-scripts-js' ], $pkg->version, [ 'strategy' => 'defer' ]);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-api';

        // Inquiries
        wp_enqueue_script('inquiry-js', get_template_directory_uri() . '/lib/js/inquiry.js', [], $pkg->version, []); // Deprecated
        wp_enqueue_script('inquiry-messages-js', get_template_directory_uri() . '/lib/js/inquiry-messages.js', [], $pkg->version, []); // Deprecated

        // Messages
        wp_enqueue_script('messages-js', get_template_directory_uri() . '/lib/js/messages-scripts.js', ['messages-api-js', 'messages-app-state-js'], $pkg->version, []);
        wp_enqueue_script('messages-api-js', get_template_directory_uri() . '/lib/js/messages-api-scripts.js', [], $pkg->version, []);
        wp_enqueue_script('messages-app-state-js', get_template_directory_uri() . '/lib/js/messages-app-state-scripts.js', ['promise-lock-js'], $pkg->version, []);
        wp_enqueue_script('promise-lock-js', get_template_directory_uri() . '/lib/js/promise-lock.js', [], $pkg->version, []);
        wp_localize_script('messages-api-js', 'messagesSiteData', [
            'siteUrl'              => site_url(),
            'userId'               => get_current_user_id(),
            'nonce'                => wp_create_nonce('wp_rest'),
        ]);
        wp_enqueue_script('nonce-refresh-js', get_template_directory_uri() . '/lib/js/nonce-refresh.js', [], $pkg->version, []);
        wp_localize_script('nonce-refresh-js', 'nonceSiteData', [
            'nonce'                => wp_create_nonce('wp_rest'),
            'nonceRefreshInterval' => NONCE_REFRESH_INTERVAL,
        ]);
        $alpine_dependencies[] = 'messages-js';

    }

    // Buyer pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/buyers/')) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, [ 'strategy' => 'async' ]);

        // Reviews
        wp_enqueue_script('review-modal-js', get_template_directory_uri() . '/lib/js/review-modal.js', [], $pkg->version, []);

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'account-cropper-scripts-js' ], $pkg->version, []);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('account-cropper-scripts-js', get_template_directory_uri() . '/lib/js/account-cropper-scripts.js', [], $pkg->version, []);
        wp_localize_script('account-cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';
    }

    // Account page
    if (str_starts_with($_SERVER['REQUEST_URI'], '/account/')) {
        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'account-cropper-scripts-js' ], $pkg->version, []);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('account-cropper-scripts-js', get_template_directory_uri() . '/lib/js/account-cropper-scripts.js', [], $pkg->version, []);
        wp_localize_script('account-cropper-scripts-js', 'cropperSiteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';
    }

    // Musician Earnings Report Form page
    if (str_starts_with($_SERVER['REQUEST_URI'], '/musician-earnings-report/')) {
        // Input component scripts
        wp_enqueue_script('tag-input-scripts-js', get_template_directory_uri() . '/lib/js/tag-input-scripts.js', [], $pkg->version, []);
    }

    // Utilities
    wp_enqueue_script('notifications-js', get_template_directory_uri() . '/lib/js/notifications.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    wp_localize_script('notifications-js', 'notificationsSiteData', [
        'userLoggedIn' => is_user_logged_in(),
        'nonce'        => wp_create_nonce('wp_rest'),
    ]);
    $alpine_dependencies[] = 'notifications-js';
    wp_enqueue_script('device-detection', get_template_directory_uri() . '/lib/js/device-detection.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    $alpine_dependencies[] = 'device-detection';
    //wp_enqueue_script('location-detect-js', get_template_directory_uri() . '/lib/js/location-detect.js', [], $pkg->version, [ 'strategy' => 'async' ]);
    //wp_localize_script('location-detect-js', 'hmLocationDetect', [ 'endpoint' => site_url('/wp-json/v1/detect-location/'), ]);
    wp_enqueue_script('htmx', get_template_directory_uri() . '/lib/js/htmx/htmx.2.0.4.bundle.min.js', [], $pkg->version, [ 'strategy' => 'defer' ]);
    wp_enqueue_script('alpinejs', get_template_directory_uri() . '/lib/js/alpinejs/alpine.3.14.8.bundle.min.js', $alpine_dependencies, $pkg->version, [ 'strategy' => 'defer' ]);
    wp_enqueue_script('animations-js', get_template_directory_uri() . '/lib/js/animations.js', [], $pkg->version, [ 'strategy' => 'async' ]);

}
add_action( 'wp_enqueue_scripts', 'hmm_scripts' );

// Inline styles
function hm_inline_theme_styles() {
    // Paths to your compiled dist files
    $theme_dir = get_template_directory();
    $style_path   = $theme_dir . '/dist/style.css';
    $tailwind_path = $theme_dir . '/dist/tailwind.css';

    echo '<style id="hm-inline-styles">' . "\n";
    if ( file_exists( $style_path ) )    { echo "/* style.css */\n" . file_get_contents( $style_path ) . "\n"; }
    if ( file_exists( $tailwind_path ) ) { echo "/* tailwind.css */\n" . file_get_contents( $tailwind_path ) . "\n"; }
    echo '</style>' . "\n";
}
add_action( 'wp_head', 'hm_inline_theme_styles', 5 ); // Hook into wp_head with priority 5 to load early in the document head

// Include
require get_template_directory() . '/lib/inc/template-functions.php';
require get_template_directory() . '/lib/inc/post-types.php';
require get_template_directory() . '/lib/inc/menus.php';
require get_template_directory() . '/lib/inc/posts.php';
require get_template_directory() . '/lib/inc/blocks.php';
require get_template_directory() . '/lib/inc/shortcodes.php';
require get_template_directory() . '/lib/inc/comments.php';
require get_template_directory() . '/lib/inc/helper.php';
require get_template_directory() . '/lib/inc/author-filter.php';
require get_template_directory() . '/html-api/html-api-rewrite-rules.php';
require get_template_directory() . '/html-api/html-api-routes.php';
require get_template_directory() . '/template-routes/template-rewrite-rules.php';
require get_template_directory() . '/template-routes/template-routes.php';
require get_template_directory() . '/lib/inc/user-mgmt.php';
require get_template_directory() . '/lib/inc/admin-panel.php';
require get_template_directory() . '/lib/inc/admin-panel-users.php';
require get_template_directory() . '/lib/inc/admin-panel-listings.php';
require get_template_directory() . '/lib/inc/sitemap.php';
require get_template_directory() . '/lib/inc/robots.php';
require get_template_directory() . '/lib/inc/404-redirects.php';
require get_template_directory() . '/lib/inc/logout.php';
// Enables htmx preload for home and pages; Removed because it was causing issues with undesired browser caching especially while logged in
// When logging in with user/pass, the page would reload from cache instead of getting the new logged in version of the page. This would need to be solved to re-enable the preload on pages and home
//require get_template_directory() . '/lib/inc/preload.php';




// Plugins





// Allow SVG upload
function add_file_types_to_uploads($file_types){
$new_filetypes = array();
$new_filetypes['svg'] = 'image/svg+xml';
$file_types = array_merge($file_types, $new_filetypes );
return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

// Remove block library CSS
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' );
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );



// Req debug
function log_incoming_request_url() {
    $request_uri = $_SERVER['REQUEST_URI'];
    $forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'N/A';
    $real_ip       = $_SERVER['HTTP_X_REAL_IP'] ?? 'N/A';
    $remote_addr   = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
    error_log( 'Request: ' . $request_uri . ' | REMOTE_ADDR: ' . $remote_addr . ' | X-Forwarded-For: ' . $forwarded_for . ' | X-Real-IP: ' . $real_ip );
}
add_action( 'init', 'log_incoming_request_url' ); // 'init' is an early action


// Change nonce lifetime for testing only
//add_filter( 'nonce_life', function( $seconds ) {
    //return 20; // seconds
//});


// Output Organization schema in the head
function hm_output_organization_schema() {
    get_template_part('template-parts/global/schema/organization-schema');
}
add_action('wp_head', 'hm_output_organization_schema');

?>
