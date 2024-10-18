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
                <div style="display:flex; justify-content:space-between; margin-top:40px;">
                    <div>
                        <table style="width:100%">
                            <tr><td>Overall Rating</td><td><?php echo get_field('_overall_rating'); ?>/5</td></tr>
                            <tr><td>Total Reviews:</td><td><?php echo get_field('_review_count'); ?></td></tr>
                            <tr><td>Average Earnings Per Gig:</td><td>$<?php echo get_field('_average_earnings'); ?></td></tr>
                            <tr><td>Average Earnings Per Performer:</td><td>$<?php echo get_field('_average_earnings_per_performer'); ?></td></tr>
                            <tr><td>Average Earnings Per Performer Per Hour:</td><td>$<?php echo get_field('_average_earnings_per_performer_per_hour'); ?></td></tr>
                        </table>
                    </div>
                    <div>
                        <div id="leaflet-map" style="height: 300px; width: 500px"></div>
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
                </div>
                <div style="display:flex; margin-top:40px">
                    <div style="height:400px; width:400px"><canvas id="pay-structure-chart"></canvas></div>
                    <div style="height:400px; width:400px"><canvas id="pay-method-chart"></canvas></div>
                    <div style="height:400px; width:400px"><canvas id="pay-speed-chart"></canvas></div>
                </div>
                <div>
                    <h2 style="padding-top: 20px">Reviews</h2>
                    <div id="venue-reviews-container"></div>
                    <script>
                        addEventListener("DOMContentLoaded", () => {
                            document.dispatchEvent(new CustomEvent('GetVenueReviews', {'detail': {
                                'reviewsContainerId': 'venue-reviews-container',
                                'payStructureChartContainerId': 'pay-structure-chart',
                                'paySpeedChartContainerId': 'pay-speed-chart',
                                'payMethodChartContainerId': 'pay-method-chart',
                                'venueId': <?php the_ID(); ?>
                            }}));
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

