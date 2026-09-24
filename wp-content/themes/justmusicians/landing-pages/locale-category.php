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
$title         = get_post_meta($post_id, 'title', true);
$region_post   = get_post_meta($post_id, 'region', true);
$locale_post   = get_post_meta($post_id, 'locale', true);
$category_post = get_post_meta($post_id, 'category', true);
$country       = get_post_meta($region_post, 'country', true);
$region_name   = get_post_meta($region_post, 'name', true);
$locale_name   = get_post_meta($locale_post, 'name', true);
$category_name = get_post_meta($category_post, 'plural_name', true);
$radius        = get_post_meta($locale_post, 'radius', true);
$location_label = $locale_name . ', ' . $region_name;
$locale_lat = null;
$locale_lng = null;
$postal_codes = [];

$locale_data = hm_location_get_city_data($country, $region_name, $locale_name);
$locale_lat = $locale_data->lat;
$locale_lng = $locale_data->lng;
$postal_codes = $locale_data->postal_codes;

// Get user collections and events
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

// Breadcrumb
$vertical = get_query_var('vertical');
$region   = get_query_var('region');
$locale   = get_query_var('locale');
$mcat     = get_query_var('mcategory');
$breadcrumb_items = [
    [ 'label' => 'Home',                         'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical), 'url' => home_url('/' . $vertical . '/') ],
    [ 'label' => 'Locations',                    'url' => home_url('/' . $vertical . '/locations/') ],
    [ 'label' => $region_name,                   'url' => home_url('/' . $vertical . '/locations/' . $region . '/') ],
    [ 'label' => $locale_name,                   'url' => home_url('/' . $vertical . '/locations/' . $region . '/' . $locale . '/') ],
    [ 'label' => $category_name ],
];

// Generate page content
get_header( null, [
    'header_arg_location_label' => $location_label,
    'header_arg_lat'            => $locale_lat,
    'header_arg_lng'            => $locale_lng,
] );

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'      => true,
    'location_label'       => $location_label,
    'hide_location_filter' => true,
    'hide_category_filter' => true,
    'title'                => $title,
    'breadcrumb_items'     => $breadcrumb_items,
    'collections_map'      => $collections_map,
    'qcategory'            => $mcat,
    'qgenre'               => '',
    'qsubgenre'            => '',
    'qinstrumentation'     => '',
    'qsetting'             => '',
    'lat'                  => $locale_lat,
    'lng'                  => $locale_lng,
    'postal_codes'         => $postal_codes,
]);

// Content
$content_post = get_post( $post_id );
if ( ! empty( $content_post->post_content ) ) { ?>
    <div class="container article-body py-32">
        <?php echo get_the_content(null, false, $post_id); ?>
    </div>
<?php }


get_template_part('template-parts/landing-pages/categories-in-locale-links', '', [
    'heading'        => "Explore other categories in $location_label",
    'exclude'        => [$post_id],
    'locale_post_id' => $locale_post,
]);

get_footer();
