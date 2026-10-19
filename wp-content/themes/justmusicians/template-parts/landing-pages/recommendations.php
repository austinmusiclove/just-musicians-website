<?php
$post_id       = $args['post_id'];
$heading       = $args['heading'];
$recommendation_ids = get_post_meta($post_id, 'recommendations', true);
?>

<section class="container article-body py-16">

    <h2 class="mb-8"><?php echo esc_html( $heading ); ?></h2>

    <div class="flex flex-col gap-8">

    <?php $rec_number = 0; foreach ( $recommendation_ids as $rec_id ) {
        $rec_listing = get_post_meta($rec_id, 'listing', true);
        $rec_title   = $rec_listing ? get_post_meta($rec_listing, 'name', true) : '';
        $rec_image = get_field('image', $rec_id);
        $rec_copy  = get_post_meta($rec_id, 'copy', true);
        if ( empty( $rec_image ) || empty( $rec_copy ) || empty( $rec_title) ) { continue; }
        $rec_number++;
    ?>

        <div class="flex flex-col">

            <h3>
                <?php echo esc_html( "$rec_number. " ); ?>
                <a href="<?php echo esc_url( get_permalink( $rec_listing ) ); ?>"><?php echo esc_html( $rec_title ); ?></a>
            </h3>

            <div class="flow-root">
                <?php if ( ! empty( $rec_image ) ) { ?>
                    <img class="w-full h-auto mb-6 md:mb-0 md:float-right md:ml-8 md:w-1/3"
                        src="<?php echo esc_url( $rec_image ); ?>"
                        alt="<?php echo esc_attr( $rec_title ); ?>"
                        loading="lazy" />
                <?php } ?>
                <?php if ( ! empty( $rec_copy ) ) { ?>
                    <p class="whitespace-pre-wrap"><?php echo wp_kses_post( $rec_copy ); ?></p>
                <?php } ?>
            </div>

        </div>

    <?php } ?>
    </div>

</section>
