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

    // Alpine
    $alpine_dependencies = ['alpinejs-resize', 'alpinejs-focus', 'device-detection'];


    // Home
    if (is_front_page() ) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', ['youtube-iframe-api', 'media-slider-js'], $pkg->version, true);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-scripts-js';

        // Alpine Intersect
        wp_enqueue_script('alpinejs-intersect', get_template_directory_uri() . '/lib/js/alpine.intersect.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-intersect';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, true);
        wp_enqueue_script('inquiry-button-js', get_template_directory_uri() . '/lib/js/inquire-button.js', [], $pkg->version, true);

        // HTMX Extensions
        wp_enqueue_script('htmx-disable-element-js', get_template_directory_uri() . '/lib/js/htmx.disable-element.1.9.12.js', ['htmx'], $pkg->version, true);
    }

    // Archive
    if (is_archive()) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, true);

        // HTMX Extensions
        wp_enqueue_script('htmx-disable-element-js', get_template_directory_uri() . '/lib/js/htmx.disable-element.1.9.12.js', ['htmx'], $pkg->version, true);
    }

    // Article and Page
    if ( is_singular(['post']) or is_page() or str_starts_with($_SERVER['REQUEST_URI'], '/blog') or str_starts_with($_SERVER['REQUEST_URI'], '/blog') ) {
        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, true);

        // HTMX Extensions
        wp_enqueue_script('htmx-disable-element-js', get_template_directory_uri() . '/lib/js/htmx.disable-element.1.9.12.js', ['htmx'], $pkg->version, true);
    }

    // Listing form
    if (str_starts_with($_SERVER['REQUEST_URI'], '/listing-form')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', ['youtube-iframe-api', 'media-slider-js'], $pkg->version, true);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-scripts-js';

        // Input component scripts
        wp_enqueue_script('tag-input-scripts-js', get_template_directory_uri() . '/lib/js/tag-input-scripts.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-urls-input-scripts-js', get_template_directory_uri() . '/lib/js/youtube-urls-input-scripts.js', [], $pkg->version, true);

        // HTMX Extensions
        wp_enqueue_script('htmx-disable-element-js', get_template_directory_uri() . '/lib/js/htmx.disable-element.1.9.12.js', ['htmx'], $pkg->version, true);

        // Alpine Intersect
        wp_enqueue_script('alpinejs-intersect', get_template_directory_uri() . '/lib/js/alpine.intersect.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-intersect';

        // Alpine Mask
        wp_enqueue_script('alpinejs-mask', get_template_directory_uri() . '/lib/js/alpine.mask.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-mask';

        // Alpine Sort
        wp_enqueue_script('alpinejs-sort', get_template_directory_uri() . '/lib/js/alpine.sort.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-sort';

        // Alpine Collapse
        wp_enqueue_script('alpinejs-collapse', get_template_directory_uri() . '/lib/js/alpine.collapse.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-collapse';

        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'cropper-scripts-js' ], $pkg->version, true);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('cropper-scripts-js', get_template_directory_uri() . '/lib/js/cropper-scripts.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'cropper-1.6-js';

        // Listing Form Image Upload handling
        wp_enqueue_script('listing-form-img-scripts', get_template_directory_uri() . '/lib/js/listing-form-img-scripts.js', [], $pkg->version, true);
    }

    // Collection pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/collection/')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', ['youtube-iframe-api', 'media-slider-js'], $pkg->version, true);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-scripts-js';

        // Alpine Intersect
        wp_enqueue_script('alpinejs-intersect', get_template_directory_uri() . '/lib/js/alpine.intersect.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-intersect';

        // Alpine Sort
        wp_enqueue_script('alpinejs-sort', get_template_directory_uri() . '/lib/js/alpine.sort.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-sort';

        // Collections
        wp_enqueue_script('collections-scripts-js', get_template_directory_uri() . '/lib/js/collections-scripts.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'collections-scripts-js';

        // Inquiries
        wp_enqueue_script('inquiry-modal-js', get_template_directory_uri() . '/lib/js/inquiry-modal.js', [], $pkg->version, true);
        wp_enqueue_script('inquiry-button-js', get_template_directory_uri() . '/lib/js/inquire-button.js', [], $pkg->version, true);
    }

    // Listing pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/listing/')) {
        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', ['youtube-iframe-api', 'media-slider-js'], $pkg->version, true);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-scripts-js';

        // Alpine Intersect
        wp_enqueue_script('alpinejs-intersect', get_template_directory_uri() . '/lib/js/alpine.intersect.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-intersect';

        // Alpine Collapse
        wp_enqueue_script('alpinejs-collapse', get_template_directory_uri() . '/lib/js/alpine.collapse.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-collapse';
    }

    // Messages pages
    if (str_starts_with($_SERVER['REQUEST_URI'], '/messages/')) {

        // Media Slider
        wp_enqueue_script('media-slider-js', get_template_directory_uri() . '/lib/js/media-slider.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-api', get_template_directory_uri() . '/lib/js/youtube-iframe-api.js', [], $pkg->version, true);
        wp_enqueue_script('youtube-iframe-scripts-js', get_template_directory_uri() . '/lib/js/youtube-iframe-scripts.js', ['youtube-iframe-api', 'media-slider-js'], $pkg->version, true);
        wp_localize_script('youtube-iframe-scripts-js', 'siteData', [ 'siteUrl' => site_url(), ]);
        $alpine_dependencies[] = 'youtube-iframe-scripts-js';

        // Alpine Collapse
        wp_enqueue_script('alpinejs-collapse', get_template_directory_uri() . '/lib/js/alpine.collapse.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-collapse';

        // Alpine Intersect
        wp_enqueue_script('alpinejs-intersect', get_template_directory_uri() . '/lib/js/alpine.intersect.min.js', [], $pkg->version, true);
        $alpine_dependencies[] = 'alpinejs-intersect';

        // HTMX Extensions
        wp_enqueue_script('htmx-disable-element-js', get_template_directory_uri() . '/lib/js/htmx.disable-element.1.9.12.js', ['htmx'], $pkg->version, true);

        // Inquiries
        wp_enqueue_script('inquiry-js', get_template_directory_uri() . '/lib/js/inquiry.js', [], $pkg->version, true);
        wp_enqueue_script('inquiry-button-js', get_template_directory_uri() . '/lib/js/inquire-button.js', [], $pkg->version, true);

        // Messages
        wp_enqueue_script('messages-js', get_template_directory_uri() . '/lib/js/messages-scripts.js', ['messages-api-js', 'messages-app-state-js'], $pkg->version, true);
        wp_enqueue_script('messages-api-js', get_template_directory_uri() . '/lib/js/messages-api-scripts.js', [], $pkg->version, true);
        wp_enqueue_script('messages-app-state-js', get_template_directory_uri() . '/lib/js/messages-app-state-scripts.js', ['promise-lock-js'], $pkg->version, true);
        wp_enqueue_script('promise-lock-js', get_template_directory_uri() . '/lib/js/promise-lock.js', [], $pkg->version, true);
        wp_localize_script('messages-api-js', 'siteData', [
            'siteUrl' => site_url(),
            'userId' => get_current_user_id(),
            'nonce'   => wp_create_nonce('wp_rest'),
        ]);
        $alpine_dependencies[] = 'messages-js';

    }

    // Account page
    if (str_starts_with($_SERVER['REQUEST_URI'], '/account/')) {
        // Cropper.js
        wp_enqueue_script('cropper-1.6-js', get_template_directory_uri() . '/lib/js/cropper.1.6.2.min.js', [ 'account-cropper-scripts-js' ], $pkg->version, true);
        wp_enqueue_style( 'cropper-1.6-css', get_template_directory_uri() . '/lib/css/cropper.1.6.2.min.css', [], $pkg->version);
        wp_enqueue_script('account-cropper-scripts-js', get_template_directory_uri() . '/lib/js/account-cropper-scripts.js', [], $pkg->version, true);
        wp_localize_script('account-cropper-scripts-js', 'siteData', [ 'templateDirectoryUri' => get_template_directory_uri(), ]);
        $alpine_dependencies[] = 'cropper-1.6-js';
    }


    // Core
    wp_enqueue_style('hmm-style', get_template_directory_uri() . '/dist/style.css', [], $pkg->version );
    wp_enqueue_style('hmm-tailwind', get_template_directory_uri() . '/dist/tailwind.css', [], $pkg->version );
    //wp_enqueue_script('hmm-js', get_template_directory_uri() . '/lib/js/scripts.js', ['jquery'], $pkg->version, true);

    // Utilities
    wp_enqueue_script('device-detection', get_template_directory_uri() . '/lib/js/device-detection.js', [], $pkg->version, true);
    wp_enqueue_script('htmx', get_template_directory_uri() . '/lib/js/htmx.2.0.4.min.js', [], $pkg->version, true);
    wp_enqueue_script('alpinejs-resize', get_template_directory_uri() . '/lib/js/alpine.resize.min.js', [], $pkg->version, true);
    wp_enqueue_script('alpinejs-focus', get_template_directory_uri() . '/lib/js/alpine.focus.min.js', [], $pkg->version, true);
    wp_enqueue_script('alpinejs', get_template_directory_uri() . '/lib/js/alpine.3.14.8.min.js', $alpine_dependencies, $pkg->version, true);
    wp_enqueue_script('animations-js', get_template_directory_uri() . '/lib/js/animations.js', [], $pkg->version, true);



}
add_action( 'wp_enqueue_scripts', 'hmm_scripts' );

// Include
require get_template_directory() . '/lib/inc/template-functions.php';
require get_template_directory() . '/lib/inc/post-types.php';
require get_template_directory() . '/lib/inc/menus.php';
require get_template_directory() . '/lib/inc/posts.php';
require get_template_directory() . '/lib/inc/blocks.php';
require get_template_directory() . '/lib/inc/shortcodes.php';
require get_template_directory() . '/lib/inc/comments.php';
require get_template_directory() . '/lib/inc/helper.php';
require get_template_directory() . '/html-api/html-api-rewrite-rules.php';
require get_template_directory() . '/html-api/html-api-routes.php';
require get_template_directory() . '/lib/inc/user-mgmt.php';
require get_template_directory() . '/lib/inc/admin-panel.php';




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


// Defer render-blocking scripts
function mind_defer_scripts( $tag, $handle, $src ) {
  $defer = array(
    'swiper-js',
  );
  if ( in_array( $handle, $defer ) ) {
     return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
  }

    return $tag;
}
add_filter( 'script_loader_tag', 'mind_defer_scripts', 10, 3 );


// Req debug
function log_incoming_request_url() {
    $request_uri = $_SERVER['REQUEST_URI'];
    error_log( 'Incoming Request URL: ' . $request_uri );
}
add_action( 'init', 'log_incoming_request_url' ); // 'init' is an early action
?>
