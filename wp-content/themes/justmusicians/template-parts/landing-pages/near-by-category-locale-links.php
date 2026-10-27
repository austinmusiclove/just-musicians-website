<?php

// Links: all categories for this locale
$query_args = [
    'post_type'      => 'lc-landing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'meta_query'     => [
        'relation' => 'AND',
        [
            'key'   => 'category',
            'value' => $args['category_post_id'],
            'compare' => '=',
        ],
        [
            'key'   => 'region',
            'value' => $args['region_post_id'],
            'compare' => '=',
        ],
    ]
];
if (isset($args['exclude'])) { $query_args['post__not_in'] = $args['exclude']; }
$query = new WP_Query( $query_args );

if ( $query->have_posts() ) { ?>

    <div class="container article-body flex py-8">
        <div class="flex flex-col w-full">
            <h2 class="mb-4"><?php echo $args['heading']; ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full">
                <?php while ( $query->have_posts() ) {
                    $query->the_post();
                    $cat_url_path = get_field('url_path');
                    $title = get_post_meta(get_the_ID(), 'title', true);
                ?>
                    <a class="text-20 text-yellow-aaa hover:text-yellow py-2 pr-2 underline inline-block"
                        href="<?php echo trailingslashit(site_url($cat_url_path)); ?>">
                        <?php echo $title; ?>
                    </a>
                <?php } wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

<?php }

