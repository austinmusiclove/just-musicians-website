<?php

$vertical = get_query_var('vertical');
$breadcrumb_items = [
    [ 'label' => 'Home',                         'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical), 'url' => home_url('/' . $vertical . '/') ],
    [ 'label' => 'Locations' ],
];

get_header();

?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container">
        <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $breadcrumb_items]); ?>
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Locations</h1>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">
        <?php
        $regions_query = new WP_Query([
            'post_type'      => 'region-landing',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'   => 'vertical',
                    'value' => $vertical,
                    'compare' => '=',
                ]
            ]
        ]);
        foreach ( $regions_query->posts as $post_id ) {
            $region_name = get_post_meta( $post_id, 'name', true );
            $region_path = get_post_meta( $post_id, 'url_path', true );
            get_template_part('template-parts/landing-pages/locale-links', '', [
                'heading'        => $region_name,
                'heading_href'   => site_url($region_path),
                'region_post_id' => $post_id,
            ]);
        }
        ?>
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
get_footer();
