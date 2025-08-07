<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JustMusicians
 */

get_header();

if (has_post_thumbnail()) {
    $header_padding = 'md:pb-14';
    $social_icons_responsive = 'flex md:hidden mb-6';
} else {
    $header_padding = 'pb-6 md:pb-14';
    $social_icons_responsive = 'flex';

}

?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden <?php echo $header_padding; ?>">
    <img class="w-32 absolute top-0 right-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10"> <!--Look here -->
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6"><?php the_title(); ?></h1>
            <div class="flex items-center gap-4 font-bold mb-8">
                <span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"><?php echo get_the_date(); ?></span>
                <span class="text-20 uppercase text-brown-dark-1 opacity-50"><?php echo the_author_meta('display_name', get_post_field('post_author')); ?></span>
            </div>
                <!--
                <div class="flex items-center gap-3 <?php echo $social_icons_responsive; ?>">
                    <img class="h-8 md:h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/facebook.svg'; ?>" />
                    <img class="h-8 md:h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/x.svg'; ?>" />
                    <img class="h-8 md:h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/email.svg'; ?>" />
                </div>
                -->
        </div>

        <?php if (has_post_thumbnail()) { ?>
            <div class="image-container sm:col-start-2 sm:col-span-8">
                <div class="w-full aspect-4/3 shadow-black-offset border-2 border-black relative">
                    <?= un_get_featured_image(get_post_thumbnail_id(), 'medium', ['class' => 'h-full w-full object-cover']) ?>
                    <!--
                    <div class="hidden md:flex flex-col items-center gap-3 absolute top-0 -right-16">
                        <img class="h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/facebook.svg'; ?>" />
                        <img class="h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/x.svg'; ?>" />
                        <img class="h-10 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/email.svg'; ?>" />
                    </div>
                    -->
                </div>
                <div class="mt-4 opacity-50"><?php the_excerpt(); ?></div>
            </div>
        <?php } ?>
    </div>
</header>


<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">
        <?php the_content(); ?>
        <div class="text-20 uppercase text-brown-dark-1 opacity-50 mt-4 font-bold">By <?php echo the_author_meta('display_name', get_post_field('post_author')); ?></div>
    </div>
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            )); ?>
        </div>
    </div>
</div>


<?php

get_footer();
