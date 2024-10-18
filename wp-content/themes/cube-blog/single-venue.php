<?php
/**
 * The template for displaying all posts
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
				while ( have_posts() ) {
					the_post();
                    $post_id = get_the_ID();

                    get_template_part( 'template-parts/content', 'single' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                }
				?>
                <h2>Overall Rating: <?php echo get_field('_overall_rating'); ?>/5 </h2>
                <p>Total Reviews: <?php echo get_field('_review_count'); ?>
                <br>Average Earnings Per Gig: $<?php echo get_field('_average_earnings'); ?>
                <br>Average Earnings Per Performer: $<?php echo get_field('_average_earnings_per_performer'); ?>
                <br>Average Earnings Per Performer Per Hour: $<?php echo get_field('_average_earnings_per_performer_per_hour'); ?></p>
                <div>
                    <div id="leaflet-map" style="height: 350px; width: 100%"></div>
                    <div id="map-init-div" zoom-level="14" enable-popups="false" latitude="<?php echo get_field( 'latitude' ); ?>" longitude="<?php echo get_field( 'longitude' ); ?>"></div>
                    <script>
                        addEventListener("DOMContentLoaded", () => {
                            document.dispatchEvent(new CustomEvent('AddMarker', {'detail': {
                                'latitude': <?php echo get_field('latitude'); ?>,
                                'longitude': <?php echo get_field('longitude'); ?>
                            }}));
                        });
                    </script>
                </div>
                <div>
                    <h2 style="padding-top: 20px">Reviews</h2>
                    <div id="venue-reviews-container"></div>
                    <script>
                        addEventListener("DOMContentLoaded", () => {
                            document.dispatchEvent(new CustomEvent('GetVenueReviews', {'detail': { 'venueId': <?php the_ID(); ?>}}));
                        });
                    </script>
                </div>
			</div><!-- .single-post-wrap -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();
?>

