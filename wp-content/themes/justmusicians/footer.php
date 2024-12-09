<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BarnRaiser
 */

?>


<?php wp_footer(); ?>

<footer class="bg-brown-dark-2 py-10">
    <div class="container grid grid-cols-12">
        <a class="col-span-2 px-6" href="#">
            <img class="w-full" src="<?php echo get_template_directory_uri() . '/lib/images/logos/logo-white.svg'; ?>" />
        </a>
    </div>

</footer>

</body>
</html>
