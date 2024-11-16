<?php
get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-page-wrapper">
                <h2><?php echo get_the_title(); ?></h2>
                <p class="text-center"><?php echo activate_account($_GET['aci']); ?></p>
			</div><!-- .single-page-wrapper  -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();
