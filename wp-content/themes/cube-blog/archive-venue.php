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

                <form autocomplete="off">
                    <div>
                        <h2 style="margin:20px 0px 20px 0px;">Top Paying Venues</h2>
                        <div style="margin:0px 20px 20px 0px;">
                            <label for="venues">Pay Structure</label>
                            <select disabled name="pay-structure" id="pay-structure">
                                <option value="">All</option>
                                <option value="Guarantee">Guarantee</option>
                                <option value="Door Deal">Door Deal</option>
                                <option value="Bar Deal">Bar Deal</option>
                            </select>
                        </div>
                        <div style="margin:0px 20px 20px 0px;">
                            <label for="venues">Sort By</label>
                            <select disabled name="pay-metric" id="pay-metric">
                                <option value="average_earnings">Earnings Per Gig</option>
                                <option value="average_earnings_per_performer">Earnings Per Performer</option>
                                <option value="average_earnings_per_hour">Earnings Per Hour</option>
                                <option value="average_earnings_per_performer_per_hour">Earnings Per Performer Per Hour</option>
                            </select>
                        </div>
                    </div>
                </form>
                <table id="top-venues-table"></table>
			</div><!-- .blog-archive -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
</div><!-- .container -->
<?php
get_footer();

