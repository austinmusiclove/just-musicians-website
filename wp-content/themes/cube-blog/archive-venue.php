<?php
/**
 * The template for displaying venue archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cube_blog
 */

get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <h1>Browse Venues</h1>
            <div id="map-init-div" zoom-level="11"></div>
            <div class="block lg:h-screen lg:sticky lg:top-0 venue-archive-map" id="leaflet-map"></div>
			<div class="blog-archive columns-3 clear">
                <?php
                /* Start the Loop */
                $args = array(
                    'post_type' => 'venue',
                    'nopaging' => true,
                    'meta_query' => array(
                        array(
                            'key' => '_review_count',
                            'value' => 0,
                            'compare' => '>'
                        )
                    )
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    while( $query->have_posts() ) {
                        $query->the_post();

                        ?>
                            <div class="coordinate-data" latitude="<?php echo get_field( 'latitude' ); ?>" longitude="<?php echo get_field( 'longitude' );?>" coordinateTitle="<?php echo get_field( 'name' ); ?>" reviewCount="<?php echo get_field( '_review_count' ); ?>" coordinateLinkUrl="<?php echo esc_url( get_permalink() ); ?>" averagePay="<?php echo get_field('_average_pay');  ?>" overallRating="<?php echo get_field('_overall_rating'); ?>"></div>
                        <?php
						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						//get_template_part( 'template-parts/content', get_post_type() );

                    }
                    wp_reset_postdata();

                }else {

					get_template_part( 'template-parts/content', 'none' );

                }
				?>
                <h2 style="padding-top:20px">Top Paying Venues</h2>
                <table>
                    <tr>
                        <th>Rank</th>
                        <th>Venue</th>
                        <th>Average Performer Wage</th>
                        <th>Review Count</th>
                        <th>Rating</th>
                    </tr>
                <?php
                $args = array(
                    'post_type' => 'venue',
                    'posts_per_page' => 20,
                    'meta_query' => array(
                        array(
                            'key' => '_review_count',
                            'value' => 0,
                            'compare' => '>'
                        )
                    ),
                    'order' => 'DEC',
                    'orderby' => 'meta_value_num',
                    'meta_key' => '_average_pay'
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    $rank = 0;
                    while( $query->have_posts() ) {
                        $rank++;
                        $query->the_post();
                        ?>
                        <tr>
                            <td><?php echo $rank ?></td>
                            <td><a href="<?php the_permalink(); ?>"><?php echo get_field('name') ?></a></td>
                            <td>$<?php echo get_field('_average_pay') ?>/hr</td>
                            <td><?php echo get_field('_review_count') ?></td>
                            <td><?php echo get_field('_overall_rating') ?>/5</td>
                        </tr>
                        <?php
                    }
                }
				?>
                </table>
			</div><!-- .blog-archive -->

			<?php
            /*
			the_posts_pagination(
				array(
					'prev_text'          => cube_blog_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'cube-blog' ) . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'cube-blog' ) . '</span>' . cube_blog_get_svg( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cube-blog' ) . ' </span>',
				)
            );
            */
            ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();

