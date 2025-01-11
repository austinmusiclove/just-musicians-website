<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cube_blog
 */

?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar( 'sidebar-2' ) || is_active_sidebar( 'sidebar-3' ) || is_active_sidebar( 'sidebar-4' ) ) : ?>
			<div id="footer-widgets" class="container">
				<?php
					get_template_part( 'inc/footer', 'widgets' );
				?>
			</div><!-- .container -->
		<?php endif; ?>

		<nav id="site-navigation" class="main-navigation navigation-menu">
			<div class="container">
				<button class="menu-toggle" aria-controls="footer-menu" aria-expanded="false">
					<?php
						echo cube_blog_get_svg( array( 'icon' => 'bars' ) );
						echo cube_blog_get_svg( array( 'icon' => 'close' ) );
					?>
					<span class="footer-menu-label"><?php esc_html_e( 'Menu', 'cube-blog' ); ?></span>
				</button>

				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer_menu',
	    			'container' 	 => false,
					'menu_id'        => 'footer-menu',
					'menu_class'     => 'nav-menu',
				) );
				?>
			</div><!-- .container -->
		</nav><!-- #site-navigation -->
		<div class="site-info">
			<div class="container">
                Copywright 2025 Just Musicians
			</div><!-- .container -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

	<a href="#page" class="to-top"></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
