<?php
$locale_url_path = $args['locale'];
$heading = $args['heading'];
$query_args = [
    'post_type'      => 'locale-landing',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'meta_query'     => [
        [
            'key'   => 'url_path',
            'value' => $locale_url_path,
            'compare' => '=',
        ]
    ]
];
$query = new WP_Query( $query_args );
$recommendation_ids = [];
if ( $query->have_posts() ) {
    $query->the_post();
    $post_id = get_the_ID();
    $recommendations = get_post_meta( $post_id, 'recommendations', true );
    $recommendation_ids = !empty( $recommendations ) ? $recommendations : [];
    wp_reset_postdata();
}
?>


<?php if ( count($recommendation_ids) > 0) { ?>
<section class="container article-body py-16">

    <h2 class="mb-8"><?php echo esc_html( $heading ); ?></h2>

    <?php if ( ! empty( $args['description'] ) ) { ?>
        <div class="mb-8 max-w-3xl">
            <p><?php echo wp_kses_post( $args['description'] ); ?></p>
        </div>
    <?php } ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <?php foreach ( $recommendation_ids as $rec_id ) {
        $organization = get_post_meta($rec_id, 'publication_name', true);
        $rec_img_id = get_field('image', $rec_id);
        $rec_img_url = wp_get_attachment_image_url($rec_img_id, 'standard-listing');
        $rec_copy  = get_post_meta($rec_id, 'copy', true);
        if ( empty( $rec_img_id ) || empty( $rec_copy ) || empty( $organization) ) { continue; }
    ?>

        <!-- Card Container -->
        <div class="flex flex-col overflow-hidden">

            <?php if ( ! empty( $rec_img_id ) ) { ?>
                <div class="w-full aspect-[4/3] overflow-hidden">
                    <img class="w-full h-full object-cover"
                         width="600"
                         height="450"
                         src="<?php echo esc_url( $rec_img_url ); ?>"
                         alt="<?php echo esc_attr( $organization ); ?> Logo"
                         loading="lazy" />
                </div>
            <?php } ?>

            <!-- Card Content Body -->
            <div class="flex flex-col flex-grow p-5">
                <h3 class="text-xl font-bold mb-3">
                    <?php echo esc_html( $organization ); ?>
                </h3>

                <?php if ( ! empty( $rec_copy ) ) { ?>
                    <p class="whitespace-pre-wrap text-sm text-gray-700 leading-relaxed"><?php echo wp_kses_post( $rec_copy ); ?></p>
                <?php } ?>
            </div>

        </div>

    <?php } ?>
    </div>

</section>
<?php } ?>
