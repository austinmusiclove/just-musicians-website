<?php

// Links: all categories for this locale
$categories_query_args = [
    'post_type'      => 'lc-landing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [
        [
            'key'   => 'locale',
            'value' => $args['locale_post_id'],
            'compare' => '=',
        ]
    ]
];
if (isset($args['exclude'])) { $categories_query_args['post__not_in'] = $args['exclude']; }
$categories_query = new WP_Query( $categories_query_args );

if ( $categories_query->have_posts() ) { ?>

    <div class="container flex justify-center py-32">
        <div class="flex flex-col items-center sm:max-w-[600px]">
            <h2 class="font-sun-motter text-center text-25 mb-4"><?php echo $args['heading']; ?></h2>
            <div class="flex items-center justify-center gap-2 flex-wrap">
                <?php while ( $categories_query->have_posts() ) {
                    $categories_query->the_post();
                    $cat_url_path = get_field('url_path');
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
