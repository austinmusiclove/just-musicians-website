<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package JustMusicians
 */

?>


<?php wp_footer(); ?>

<footer class="bg-brown-dark-2 py-16">
    <div class="container grid md:grid-cols-6 lg:grid-cols-12 gap-20">

        <div class="col md:col-span-2 lg:col-span-3 justify-center hidden md:flex">
            <a href="#">
                <img class="w-40" src="<?php echo get_template_directory_uri() . '/lib/images/logos/logo-white.svg'; ?>" />
            </a>
        </div>

        <div class="col md:col-span-4 lg:col-span-9 flex flex-wrap flex-row gap-x-24 gap-y-8">

            <!-- Footer Menu 1 -->
            <div class="footer-menu">
                <div class="font-sans text-20 font-bold mb-4">Genres</div>
                <ul>
                    <li><a href="#">Country</a></li>
                    <li><a href="#">Indie Rock</a></li>
                    <li><a href="#">Latin</a></li>
                    <li><a href="#">Psychedelic</a></li>
                </ul>
            </div>

            <!-- Footer Menu 2 -->
            <div class="footer-menu">
                <div class="font-sans text-20 font-bold mb-4">Categories</div>
                <ul>
                    <li><a href="#">Country</a></li>
                    <li><a href="#">Indie Rock</a></li>
                    <li><a href="#">Latin</a></li>
                    <li><a href="#">Psychedelic</a></li>
                </ul>
            </div>

            <!-- Footer Menu 3 -->
            <div class="footer-menu">
                <div class="font-sans text-20 font-bold mb-4">Types</div>
                <ul>
                    <li><a href="#">Band</a></li>
                    <li><a href="#">DJ</a></li>
                    <li><a href="#">Musician</a></li>
                    <li><a href="#">Solo/DJ</a></li>
                </ul>
            </div>

        </div>
    </div>

</footer>

</body>
</html>
