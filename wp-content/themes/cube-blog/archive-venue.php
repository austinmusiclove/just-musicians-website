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
			<div id="main-content-container" class="blog-archive columns-3 clear">
                <h2 style="padding-top:20px">Top Paying Venues</h2>
                <table id="top-venues-table"></table>
			</div><!-- .blog-archive -->
            <script>
                addEventListener("DOMContentLoaded", () => {
                    document.dispatchEvent(new CustomEvent('GetVenues', {'detail': {'payStructure': null, 'payMetric': '_average_earnings'}}));
                });
            </script>

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

