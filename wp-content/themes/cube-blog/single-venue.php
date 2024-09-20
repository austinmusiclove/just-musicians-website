<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cube_blog
 */

get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-post-wrap">
				<?php
				while ( have_posts() ) :
					the_post();
                    $post_id = get_the_ID();

					get_template_part( 'template-parts/content', 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
                <div>
                    <div id="leaflet-map" style="height: 350px; width: 100%"></div>
                    <div id="map-init-div" zoom-level="14" enable-popups="false" latitude="<?php echo get_field( 'latitude' ); ?>" longitude="<?php echo get_field( 'longitude' ); ?>"></div>
                    <div class="coordinate-data" latitude="<?php echo get_field( 'latitude' ); ?>" longitude="<?php echo get_field( 'longitude' ); ?>"></div>
                    <div class="coordinate-data" latitude="<?php echo get_field( 'latitude' ); ?>" longitude="<?php echo get_field( 'longitude' );?>" venueName="<?php echo get_field( 'name' ); ?>" ></div>
                </div>
                <div>
                    <h2>Reviews</h2>
                    <?php
                        $args = array(
                            'post_type' => 'venue_review',
                            'posts_per_page' => 5,
                            'meta_query' => array(
                                array(
                                    'key' => 'venue',
                                    'value' => $post_id,
                                    'compare' => '=='
                                )
                            )
                        );
                        $query = new WP_Query($args);
                        if ($query->have_posts()) :
                            while( $query->have_posts() ) :
                                $query->the_post();
                                ?>
                                    <h3><?php echo get_field('overall_rating'); ?>/5</h3>
                                    <p><?php echo get_field('review'); ?></p>
                                <?php
                            endwhile;
                        endif;
                        wp_reset_postdata();
                    ?>
                </div>
			</div><!-- .single-post-wrap -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();
?>

