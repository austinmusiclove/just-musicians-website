<?php

// Links: all categories for this locale
$categories_query_args = [
    'post_type'      => 'lc-landing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
];
if (isset($args['exclude'])) { $categories_query_args['post__not_in'] = $args['exclude']; }
$categories_query = new WP_Query( $categories_query_args );

if ( $categories_query->have_posts() ) { ?>

    <div class="container flex py-8">
        <div class="flex flex-col w-full">
            <h2 class="font-sun-motter text-25 mb-4"><?php echo $args['heading']; ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full">
                <?php while ( $categories_query->have_posts() ) {
                    $categories_query->the_post();
                    $url_path = get_field('url_path');
                    $title = get_field('title');
                ?>
                    <a class="text-20 text-yellow py-2 pr-2 underline inline-block"
                        href="<?php echo site_url($url_path); ?>">
                        <?php echo $title; ?>
                    </a>
                <?php } wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

<?php }
