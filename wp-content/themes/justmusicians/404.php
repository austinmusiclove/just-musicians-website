<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package JustMusicians
 */

get_header();
?>

<div class="font-sun-motter text-center px-4 pb-28 pt-12 sm:py-20 relative mb-4 xl:mb-0 h-[70vh] flex items-center justify-center flex-col">

    <div class="pb-16 relative z-10">
        <span class="text-22 block text-center mb-2">You’ve wandered off the map, partner. <br>Let’s get you back to the <a href="<?php echo site_url(); ?>" class="text-yellow">main road.</a></span>
        <p class="text-20 mb-4">Ain’t nothin’ here but tumbleweeds.</p>

        <a href="<?php echo site_url(); ?>"><button class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3">Home Page</button></a>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>

<?php
get_footer();
