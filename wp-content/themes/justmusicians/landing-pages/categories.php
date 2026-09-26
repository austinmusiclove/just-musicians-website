<?php

$vertical = get_query_var('vertical');

$breadcrumb_items = [
    [ 'label' => 'Home',                         'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical), 'url' => home_url('/' . $vertical . '/') ],
    [ 'label' => 'Categories' ],
];

get_header();

?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container">
        <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $breadcrumb_items]); ?>
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Live Music Categories</h1>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">
        <?php
        $categories_query = new WP_Query([
            'post_type'      => 'category-landing',
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
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full">
            <?php while ( $categories_query->have_posts() ) {
                $categories_query->the_post();
                $url_path = get_field('url_path');
                $title = get_field('plural_name');
            ?>
                <a class="text-20 text-yellow py-2 pr-2 underline inline-block"
                    href="<?php echo site_url($url_path); ?>">
                    <?php echo $title; ?>
                </a>
            <?php } wp_reset_postdata(); ?>
        </div>
    </div>
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            )); ?>
        </div>
    </div>
</div>


<?php
get_footer();
