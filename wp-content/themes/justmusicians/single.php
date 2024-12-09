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
 * @package BarnRaiser
 */

get_header();

?>

<header class="bg-yellow-light pt-24 pb-14 relative">
    <img class="w-32 absolute top-0 right-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />
    <div class="container grid grid-cols-10 gap-24">
        <div class="col-span-7">
            <h1 class="font-bold text-40 mb-6"><?php the_title(); ?></h1>
            <div class="flex items-center gap-4 font-bold mb-8">
                <span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block">Nov 11, 2024</span>
                <span class="text-20 uppercase text-brown-dark-1 opacity-50">John Filippone</span>
            </div>
            <?php if (!has_post_thumbnail()) { ?>
                <div class="flex items-center gap-3">
                    <img class="h-10 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/facebook.svg'; ?>" />
                    <img class="h-10 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/x.svg'; ?>" />
                    <img class="h-10 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/email.svg'; ?>" />
                </div>
            <?php } ?>
        </div>
    </div>
</header>


<div class="container grid grid-cols-10 gap-24 py-8">
    <div class="col-span-7 article-body">
        <?php the_content(); ?>
    </div>
    <div class="col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/global/form-quote', '', array()); ?> 
        </div>
    </div>
</div>


<?php

get_footer();
