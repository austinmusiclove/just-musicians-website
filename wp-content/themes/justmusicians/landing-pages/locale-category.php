<?php

// Post data
global $wp;
$post_id = null;
$current_path = $wp->request;
$query = new WP_Query( [
    'post_type'      => 'lc-landing',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'fields'         => 'ids',
    'meta_query'     => [
        [
            'key'   => 'url_path',
            'value' => $current_path,
            'compare' => '=',
        ]
    ]
]);
if ( ! empty( $query->posts ) ) {
    $post_id = $query->posts[0];
} else {
    status_header(404);
    include( get_query_template( '404' ) );
    exit;
}
$title            = get_post_meta($post_id, 'title', true);
$meta_title       = get_post_meta($post_id, 'meta_title', true);
$description      = get_post_meta($post_id, 'description', true);
$meta_description = get_post_meta($post_id, 'meta_description', true);
$region_post      = get_post_meta($post_id, 'region', true);
$locale_post      = get_post_meta($post_id, 'locale', true);
$category_post    = get_post_meta($post_id, 'category', true);
$prioritize_zip   = get_post_meta($post_id, 'prioritize_zip', true);
$country          = get_post_meta($region_post, 'country', true);
$region_name      = get_post_meta($region_post, 'name', true);
$locale_name      = get_post_meta($locale_post, 'name', true);
$locale_url_path  = get_post_meta($locale_post, 'url_path', true);
$category_name    = get_post_meta($category_post, 'plural_name', true);
$category_slug    = get_post_meta($category_post, 'taxonomy_slug', true);

// Set meta title and description
if ( ! empty( $meta_title ) ) {
    add_filter( 'pre_get_document_title', function ( $title ) use ( $meta_title ) {
            return esc_html( $meta_title );
    });
}
if ( ! empty( $meta_description ) ) {
    add_action( 'wp_head', function () use ( $meta_description ) {
        echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
    }, 1);
}

$location_label = $locale_name . ', ' . $region_name;
$locale_lat = null;
$locale_lng = null;
$postal_codes = [];

$locale_data = hm_location_get_city_data($country, $region_name, $locale_name);
$locale_lat = $locale_data->lat;
$locale_lng = $locale_data->lng;
if ($prioritize_zip) {
    $postal_codes = $locale_data->postal_codes;
}

// Get user collections and events
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

// Prioritize the listings referenced by the priority_listings meta field in search
$priority_listings = get_post_meta($post_id, 'priority_listings', true);
$priority_listings = is_array( $priority_listings ) ? array_map( 'intval', $priority_listings ) : [];

// Breadcrumb
// Future state will be Home / Live Music in Locale / Category
$breadcrumb_items = [
    [ 'label' => 'Home', 'url' => home_url('/') ],
    [ 'label' => $category_name . ' in ' . $locale_name ],
];

// Generate page content
get_header( null, [
    'header_arg_location_label' => $location_label,
    'header_arg_lat'            => $locale_lat,
    'header_arg_lng'            => $locale_lng,
] );

?><main role="main"><?php

get_template_part('template-parts/landing-pages/hero-section-basic', '', [
    'breadcrumb_items' => $breadcrumb_items,
    'heading'          => $title,
    'description'      => $description,
]);
get_template_part('template-parts/search/search-page', '', [
    'send_first_page'      => true,
    'location_label'       => $location_label,
    'hide_location_filter' => true,
    'hide_category_filter' => true,
    'hide_subgenre_filter' => false,
    'collections_map'      => $collections_map,
    'qcategory'            => $category_slug,
    'qgenre'               => '',
    'qsubgenre'            => '',
    'qinstrumentation'     => '',
    'qsetting'             => '',
    'lat'                  => $locale_lat,
    'lng'                  => $locale_lng,
    'postal_codes'         => $postal_codes,
    'priority_listings'    => $priority_listings,
]);

// Recommendations
get_template_part('template-parts/landing-pages/recommendations', '', [
    'post_id'       => $post_id,
    'heading'       => "Curated $category_name For Hire in $locale_name, $region_name",
]);

// Content
$content_post = get_post( $post_id );
if ( ! empty( $content_post->post_content ) ) { ?>
    <div class="container article-body py-16">
        <?php echo get_the_content(null, false, $post_id); ?>
    </div>
<?php }

get_template_part('template-parts/landing-pages/categories-in-locale-links', '', [
    'heading'        => "Explore other categories in $location_label",
    'exclude'        => [$post_id],
    'locale_post_id' => $locale_post,
]);
get_template_part('template-parts/landing-pages/near-by-category-locale-links', '', [
    'heading'        => "Explore $category_name near by",
    'exclude'        => [$post_id],
    'category_post_id' => $category_post,
    'region_post_id'   => $region_post,
]);
/*
get_template_part('template-parts/landing-pages/partners', '', [
    'heading'     => "Trusted $locale_name Partners & Affiliates",
    'description' => "We proudly collaborate with local businesses and organizations who share our passion for keeping $locale_name's live music scene thriving. Discover some of our trusted partners and affiliates that help us bring great music to life.",
    'locale'      => $locale_url_path,
]);
*/

?></main><?php

get_footer();
