<?php
get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-page-wrapper">
                <h2>Request a Password Reset</h2>
                <form action="<?php echo get_site_url() . '/wp-login.php?action=lostpassword'; ?>" method="POST">
                    <div>
                        <label for="log">Email Address</label>
                        <div><input id="email" name="user_login" type="email" autocomplete="email" required></div>
                    </div>
                    <div><button type="submit">Reset Password</button></div>
                </form>
			</div><!-- .single-page-wrapper  -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();
