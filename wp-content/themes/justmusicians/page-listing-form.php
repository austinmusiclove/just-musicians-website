<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

?>
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden <?php echo $header_padding; ?>">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>

<div class="container md:grid md:grid-cols-12 py-8 min-h-[500px]">
    <div class="col-span-12 lg:col-span-6">
        <p>This page is under construction.. If you were sent a link to sign up for Just Musicians in 2024, the link has expired. But I will be sending out more once the new website is ready. Thank you for your patience.</p>
    </div>
    <div class="hidden lg:block md:col-span-1"></div>
</div>

<?php
get_footer();
