<?php
/**
 * The template for displaying checkout success
 *
 * @package JustMusicians
 */

get_header();
?>

<div class="font-sun-motter text-center px-4 pb-28 pt-12 sm:py-20 relative mb-4 xl:mb-0 h-[70vh] flex items-center justify-center flex-col">

    <div class="pb-16 relative z-10">
        <?php if (is_user_logged_in()) { ?>
            <span class="text-22 block text-center mb-2">Checkout was successful!</span>
            <p class="text-20 mb-4">Check your email for your order confirmation.</p>
            <a href="<?php echo site_url('/subscriptions/'); ?>"><button class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3">My Subscriptions</button></a>
        <?php } else { ?>
            <span class="text-22 block text-center mb-2">Checkout was successful!</span>
            <p class="text-20 mb-4">Check your email for your order confirmation and to set up your account.</p>
        <?php } ?>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>

<?php
get_footer();
