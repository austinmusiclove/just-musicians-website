<?php

if ( empty( $args['region_post_id'] ) ) { return; }

$locale_query_args = [
    'post_type'      => 'locale-landing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        [
            'key'   => 'region',
            'value' => $args['region_post_id'],
            'compare' => '=',
        ]
    ]
];
$locale_query = new WP_Query( $locale_query_args );

if ( ! $locale_query->have_posts() ) { return; }

?>

<div class="container flex py-16">
    <div class="flex flex-col w-full">

        <?php if ( !empty($args['heading']) and !empty($args['heading_href']) ) { ?>
            <h2 class="font-sun-motter text-25 mb-4">
                <a href="<?php echo $args['heading_href']; ?>"><?php echo $args['heading']; ?></a>
            </h2>
        <?php } else if ( !empty($args['heading']) ) { ?>
            <h2 class="font-sun-motter text-25 mb-4"><?php echo $args['heading']; ?></h2>
        <?php } ?>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full">
            <?php foreach ( $locale_query->posts as $post_id ) {
                $locale_name = get_post_meta( $post_id, 'name', true );
                $locale_path = get_post_meta( $post_id, 'url_path', true );
                if ( empty( $locale_name ) || empty( $locale_path ) ) { continue; }
            ?>
                <a class="text-20 text-yellow py-2 pr-2 underline inline-block"
                    href="<?php echo site_url($locale_path); ?>">
                    <?php echo $locale_name; ?>
                </a>
            <?php } ?>
        </div>

    </div>
</div>
