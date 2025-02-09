<?php
/**
 * Cube Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cube_blog
 */

if ( ! function_exists( 'cube_blog_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cube_blog_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Cube Blog, use a find and replace
		 * to change 'cube-blog' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'cube-blog', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary_menu' 		=> esc_html__( 'Primary Menu', 'cube-blog' ),
			'footer_menu' 		=> esc_html__( 'Footer Menu', 'cube-blog' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'cube_blog_custom_background_args', array(
			'default-color' => 'f8f8f8',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/*
		* This theme styles the visual editor to resemble the theme style,
		* specifically font, colors, icons, and column width.
		*/
		add_editor_style( array( '/assets/css/editor-style.css', cube_blog_get_fonts_url() ) );

		// Gutenberg support
		add_theme_support( 'editor-color-palette', array(
	       	array(
				'name' => esc_html__( 'Blue', 'cube-blog' ),
				'slug' => 'blue',
				'color' => '#2c7dfa',
	       	),
	       	array(
	           	'name' => esc_html__( 'Green', 'cube-blog' ),
	           	'slug' => 'green',
	           	'color' => '#07d79c',
	       	),
	       	array(
	           	'name' => esc_html__( 'Orange', 'cube-blog' ),
	           	'slug' => 'orange',
	           	'color' => '#ff8737',
	       	),
	       	array(
	           	'name' => esc_html__( 'Black', 'cube-blog' ),
	           	'slug' => 'black',
	           	'color' => '#2f3633',
	       	),
	       	array(
	           	'name' => esc_html__( 'Grey', 'cube-blog' ),
	           	'slug' => 'grey',
	           	'color' => '#82868b',
	       	),
	   	));

		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-font-sizes', array(
		   	array(
		       	'name' => esc_html__( 'small', 'cube-blog' ),
		       	'shortName' => esc_html__( 'S', 'cube-blog' ),
		       	'size' => 12,
		       	'slug' => 'small'
		   	),
		   	array(
		       	'name' => esc_html__( 'regular', 'cube-blog' ),
		       	'shortName' => esc_html__( 'M', 'cube-blog' ),
		       	'size' => 16,
		       	'slug' => 'regular'
		   	),
		   	array(
		       	'name' => esc_html__( 'larger', 'cube-blog' ),
		       	'shortName' => esc_html__( 'L', 'cube-blog' ),
		       	'size' => 36,
		       	'slug' => 'larger'
		   	),
		   	array(
		       	'name' => esc_html__( 'huge', 'cube-blog' ),
		       	'shortName' => esc_html__( 'XL', 'cube-blog' ),
		       	'size' => 48,
		       	'slug' => 'huge'
		   	)
		));
		add_theme_support('editor-styles');
		add_theme_support( 'wp-block-styles' );
	}
endif;
add_action( 'after_setup_theme', 'cube_blog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cube_blog_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'cube_blog_content_width', 790 );
}
add_action( 'after_setup_theme', 'cube_blog_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cube_blog_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'cube-blog' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'cube-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'cube-blog' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'cube-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'cube-blog' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'cube-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'cube-blog' ),
			'id'            => 'sidebar-4',
			'description'   => __( 'Add widgets here to appear in your footer.', 'cube-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'cube_blog_widgets_init' );

/**
* Enqueue theme fonts.
*/
function cube_blog_fonts() {
	$fonts_url = cube_blog_get_fonts_url();

	// Load Fonts if necessary.
	if ( $fonts_url ) {
		require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );
		wp_enqueue_style( 'cube-blog-fonts', wptt_get_webfont_url( $fonts_url ), array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'cube_blog_fonts', 1 );
add_action( 'enqueue_block_editor_assets', 'cube_blog_fonts', 1 );

/**
 * Retrieve webfont URL to load fonts locally.
 */
function cube_blog_get_fonts_url() {
	$font_families = array(
		'Jost:300,400,500,600,700',
	);

	$query_args = array(
		'family'  => urlencode( implode( '|', $font_families ) ),
		'subset'  => urlencode( 'latin,latin-ext' ),
		'display' => urlencode( 'swap' ),
	);

	return apply_filters( 'cube_blog_get_fonts_url', add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
}

/**
 * Enqueue scripts and styles.
 */
function cube_blog_scripts() {

	wp_enqueue_style( 'cube-blog-blocks', get_template_directory_uri() . '/assets/css/blocks.css' );

	wp_enqueue_style( 'slick-theme-css', get_template_directory_uri() . '/assets/css/slick-theme.css' );

	wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/css/slick.css' );

	wp_enqueue_style( 'cube-blog-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery-slick', get_template_directory_uri() . '/assets/js/slick.js', array('jquery'), '2017417', true );

	wp_enqueue_script( 'cube-blog-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'cube-blog-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array('jquery'), '1.0', true );

	$cube_blog_l10n = array(
		'quote'          => cube_blog_get_svg( array( 'icon' => 'angle-down' ) ),
		'expand'         => esc_html__( 'Expand child menu', 'cube-blog' ),
		'collapse'       => esc_html__( 'Collapse child menu', 'cube-blog' ),
		'icon'           => cube_blog_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) ),
	);

	wp_localize_script( 'cube-blog-navigation', 'cube_blog_l10n', $cube_blog_l10n );

	wp_enqueue_script( 'cube-blog-custom-script', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    // custom js
	wp_enqueue_script( 'jm-custom-js', get_template_directory_uri() . '/build/index.js', array('leaflet-js', 'leaflet-clustering-js', 'chart-js'), '1.0', true );
    // Leaflet Maps
	wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_style( 'leaflet-clustering-css', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css' );
    wp_enqueue_style( 'leaflet-clustering-css-default', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css' );
	wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    wp_enqueue_script( 'leaflet-clustering-js', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js', array('leaflet-js'), null, true );
    // Charts
	wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js');
    // Youtube iFrame API
	wp_enqueue_script( 'google-iframe-api', 'https://www.youtube.com/iframe_api');
    // Cropper.js
	wp_enqueue_style( 'cropper-css', 'https://unpkg.com/cropperjs/dist/cropper.min.css');
	wp_enqueue_script( 'cropper-js', 'https://unpkg.com/cropperjs');

    wp_localize_script('jm-custom-js', 'siteData', array(
        'root_url' => get_site_url(),
        'url_path' => $_SERVER['REQUEST_URI'],
        'request_method' => $_SERVER['REQUEST_METHOD'],
        'venues_api_url' => get_site_url() . '/wp-json/v1/venues',
        'venue_post_id_by_name_api_url' => get_site_url() . '/wp-json/v1/venues/id',
        'venues_by_post_id_batch_api_url' => get_site_url() . '/wp-json/v1/venues/batch',
        'performance_by_id_api_url' => get_site_url() . '/wp-json/v1/performances/id',
        'artist_api_url' => get_site_url() . '/wp-json/v1/artists',
        'venue_reviews_api_url' => get_site_url() . '/wp-json/v1/venue_reviews',
        'venue_reviews_batch_api_url' => get_site_url() . '/wp-json/v1/venue_reviews/batch',
        'user_registration_api_url' => get_site_url() . '/wp-json/v1/users/register',
        'get_taxonomy_terms_api_url' => get_site_url() . '/wp-json/v1/taxonomies/terms',
        'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action( 'wp_enqueue_scripts', 'cube_blog_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 *
 * @since Cube Blog 1.0.0
 */
function cube_blog_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'cube-blog-block-editor-style', get_theme_file_uri( '/assets/css/editor-blocks.css' ) );
	// Add custom fonts.
	wp_enqueue_style( 'cube-blog-fonts', cube_blog_get_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'cube_blog_block_editor_styles' );

/**
 * Removing category text from category page.
 */
function cube_blog_category_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'cube_blog_category_title' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * SVG icons functions and filters.
 */
require get_template_directory() . '/inc/icon-functions.php';

/* Admin Bar */
add_action('wp_loaded', 'noAdminBar');
function noAdminBar() {
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) >= 1 and $currentUser->roles[0] != 'administrator') {
        show_admin_bar(false);
    }
}

/* Handle Redirects */
function redirect_path($from_path, $to_path) {
    if ($_SERVER['REQUEST_URI'] == $from_path) {
        wp_redirect($to_path, 301, false);
        exit;
    }
}
redirect_path('/venue', '/venues');

/* Constants */
$instagram_url_prefix = 'https://instagram.com/';
$tiktok_url_prefix = 'https://tiktok.com/';
$x_url_prefix = 'https://x.com/';
