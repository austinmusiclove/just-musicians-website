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

// Breadcrumb, Heading, Content

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'      => true,
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
    'area_served'          => [
        'city'  => $locale_name,
        'state' => $region_name,
        'lat'   => $locale_lat,
        'lng'   => $locale_lng,
    ],
]);

// Content
$content_post = get_post( $post_id );
if ( ! empty( $content_post->post_content ) ) { ?>
    <div class="container py-32">
        <?php echo get_the_content(null, false, $post_id); //echo apply_filters( 'the_content', $content_post->post_content ); ?>
    </div>
<?php }


// Links
$categories_query = new WP_Query( [
    'post_type'      => 'lc-landing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [
        [
            'key'   => 'locale',
            'value' => $locale_post,
            'compare' => '=',
        ]
    ]
]);
if ( $categories_query->have_posts() ) { ?>

    <div class="container flex justify-center py-32">
        <div class="flex flex-col items-center sm:max-w-[600px]">
            <h2 class="font-sun-motter text-center text-25 mb-4">Explore other categories in <?php echo $location_label; ?></h2>
            <div class="flex items-center justify-center gap-2 flex-wrap">
                <?php while ( $categories_query->have_posts() ) {
                    $categories_query->the_post();
                    $cat_url_path = get_field('url_path');
                    if ($cat_url_path == $current_path) { continue; }
                    $cat_name = get_post_meta(get_field('category'), 'plural_name', true);
                ?>
                    <a class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light inline-block"
                        href="<?php echo site_url($cat_url_path); ?>">
                        <?php echo $cat_name; ?>
                    </a>
                <?php } wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

<?php }

get_footer();
