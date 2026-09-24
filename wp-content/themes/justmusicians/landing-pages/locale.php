<?php

// Post data
global $wp;
$post_id = null;
$current_path = $wp->request;
$query = new WP_Query( [
    'post_type'      => 'locale-landing',
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

$locale_name = get_post_meta($post_id, 'name', true);
$region_post = get_post_meta($post_id, 'region', true);
$region_name = get_post_meta($region_post, 'name', true);

$vertical = get_query_var('vertical');
$region   = get_query_var('region');
$breadcrumb_items = [
    [ 'label' => 'Home',                           'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical),   'url' => home_url('/' . $vertical . '/') ],
    [ 'label' => 'Locations',                      'url' => home_url('/' . $vertical . '/locations/') ],
    [ 'label' => $region_name,                     'url' => home_url('/' . $vertical . '/locations/' . $region . '/') ],
    [ 'label' => $locale_name ],
];

get_header();

?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container">
        <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $breadcrumb_items]); ?>
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php echo $locale_name . ', ' . $region_name; ?></h1>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">
        <?php echo get_the_content(null, false, $post_id); ?>
    </div>
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', [
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            ]); ?>
        </div>
    </div>
</div>


<?php

get_template_part('template-parts/landing-pages/categories-in-locale-links', '', [
    'heading'        => "Explore categories in $locale_name",
    'locale_post_id' => $post_id,
]);

get_footer();
