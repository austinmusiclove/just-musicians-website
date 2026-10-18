
<?php

$recommendation_ids = get_field('recommendations', $args['post_id']);

if ( ! empty( $recommendation_ids ) ) { ?>

<div id="press-features">

    <h2 class="text-22 font-bold mb-5">Press Features</h2>

    <div class="flex flex-col gap-4">
        <?php foreach ( $recommendation_ids as $rec_id ) {
            $excerpt          = get_post_meta( $rec_id, 'excerpt', true );
            $verb             = get_post_meta( $rec_id, 'verb', true );
            $article_title    = get_post_meta( $rec_id, 'article_title', true );
            $article_url      = get_post_meta( $rec_id, 'article_url', true );
            $publication_name = get_post_meta( $rec_id, 'publication_name', true );
            $publication_url  = get_post_meta( $rec_id, 'publication_url', true );
            if ( empty( $excerpt ) ) { continue; }
        ?>

            <div>

                <h3 class="text-20 mb-2">
                    <?php echo esc_html( $verb . ' ' ); ?>
                    <?php if ( ! empty( $article_url ) ) { ?>
                        <a class="hover:underline text-yellow" href="<?php echo esc_url( $article_url ); ?>"><?php echo esc_html( $article_title ); ?></a>
                    <?php } else { echo esc_html( $article_title ); } ?>
                    by
                    <?php if ( ! empty( $publication_url ) ) { ?>
                        <a class="hover:underline text-yellow" href="<?php echo esc_url( $publication_url ); ?>"><?php echo esc_html( $publication_name ); ?></a>
                    <?php } else { echo esc_html( $publication_name ); } ?>
                </h3>

                <figure class="bg-yellow-10 border-l-4 border-yellow rounded-r px-5 py-4">
                    <blockquote class="whitespace-pre-wrap italic text-brown-dark-1"><?php echo wp_kses_post( $excerpt ); ?></blockquote>
                </figure>

            </div>

        <?php } ?>
    </div>

</div>

<?php } ?>
