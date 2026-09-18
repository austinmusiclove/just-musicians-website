<?php

$vertical = get_query_var('vertical');
$region   = get_query_var('region');
$locale   = get_query_var('locale');
$mcat     = get_query_var('mcategory');

$breadcrumb_items = [
    [ 'label' => 'Home',            'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical), 'url' => home_url('/' . $vertical . '/') ],
    [ 'label' => 'Locations',       'url' => home_url('/' . $vertical . '/locations/') ],
    [ 'label' => get_label_from_slug($region),   'url' => home_url('/' . $vertical . '/locations/' . $region . '/') ],
    [ 'label' => get_label_from_slug($locale),   'url' => home_url('/' . $vertical . '/locations/' . $region . '/' . $locale . '/') ],
    [ 'label' => get_label_from_slug($mcat) ],
];

get_header();

?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container">
        <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $breadcrumb_items]); ?>
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Locale Category Page</h1>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">
        Content
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
