<?php
/**
 * JustMusicians functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JustMusicians
 */

if ( ! function_exists( 'JustMusicians_setup' ) ) :
	function JustMusicians_setup() {
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
add_action( 'after_setup_theme', 'JustMusicians_setup' );


// Enqueue Scripts and Styles
function justmusicians_scripts() {
		$pkg = json_decode(file_get_contents('package.json', true));
		global $post;

		// Core
		wp_enqueue_style('justmusicians-style', get_template_directory_uri() . '/dist/style.css', [], $pkg->version );
		wp_enqueue_style('justmusicians-tailwind', get_template_directory_uri() . '/dist/tailwind.css', [], $pkg->version );
		wp_enqueue_script('justmusicians-js', get_template_directory_uri() . '/lib/js/scripts.js', ['jquery'], $pkg->version, true);

		// Utilities


		// Home
		if (is_front_page() ) {
		}

		// Archive
		if (is_archive()) {
		}

		// Article and Page
		if ( is_singular(array( 'post')) || is_page() ) {
		}






}
add_action( 'wp_enqueue_scripts', 'justmusicians_scripts' );

// Include
require get_template_directory() . '/lib/inc/template-functions.php';
require get_template_directory() . '/lib/inc/post-types.php';
require get_template_directory() . '/lib/inc/menus.php';
require get_template_directory() . '/lib/inc/posts.php';
require get_template_directory() . '/lib/inc/blocks.php';
require get_template_directory() . '/lib/inc/shortcodes.php';
require get_template_directory() . '/lib/inc/comments.php';


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


/**
 * Disable jQuery Migrate in WordPress.
 *
 * @author Guy Dumais.
 * @link https://en.guydumais.digital/disable-jquery-migrate-in-wordpress/
 */
add_filter( 'wp_default_scripts', $af = static function( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }    
}, PHP_INT_MAX );
unset( $af );


