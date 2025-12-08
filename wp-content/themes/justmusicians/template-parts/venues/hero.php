<?php

if (has_post_thumbnail()) {
    $header_padding = 'md:pb-14';
    $social_icons_responsive = 'sm:mb-36';
} else {
    $header_padding = 'pb-6 md:pb-14';
    $social_icons_responsive = '';
}

?>

<!-- Hero Section -->
<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden <?php echo $header_padding; ?>">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 sm:gap-x-24 gap-y-2 sm:gap-y-10"> <!--Look here -->


        <!-- Headings -->
        <div class="sm:col-span-4 pr-8 sm:pr-0">

            <!-- Title -->
            <h1 class="font-bold text-32 md:text-40 mb-6"><?php the_title(); ?></h1>

            <!-- Address-->
            <div class="flex items-center gap-4 font-bold mb-4">
                <!--<span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"></span>-->
                <span class="text-20 uppercase text-brown-dark-1 opacity-50"><?php echo get_field('street_address') . ', ' . get_field('address_locality') . ', ' . get_field('address_region') . ' ' . get_field('postal_code'); ?></span>
            </div>

            <!-- Rating Stars -->
            <div class="flex gap-x-1 text-yellow w-32 mb-4">
                <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => get_field('_overall_rating') ]); ?>
            </div>

            <!-- Socials and contact -->
            <div class="flex items-center gap-3 <?php echo $social_icons_responsive; ?>">
                <?php if (!empty(get_field('facebook_url'))) { ?>
                    <a href="<?php echo get_field('facebook_url'); ?>" target="_blank">
                        <img class="h-8 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/facebook.svg'; ?>" />
                    </a>
                <?php } ?>
                <?php if (!empty(get_field('twitter_url'))) { ?>
                    <a href="<?php echo get_field('twitter_url'); ?>" target="_blank">
                        <img class="h-8 cursor-pointer hover:scale-105" src="<?php echo get_template_directory_uri() . '/lib/images/icons/share/x.svg'; ?>" />
                    </a>
                <?php } ?>
            </div>

        </div>


        <!-- Thumbnail -->
        <?php if (has_post_thumbnail()) { ?>
            <div class="image-container sm:col-span-4 md:col-span-5">
                <div class="w-full aspect-4/3 shadow-black-offset border-2 border-black relative">
                    <?= un_get_featured_image(get_post_thumbnail_id(), 'medium', ['class' => 'h-full w-full object-cover']) ?>
                </div>
                <div class="mt-4 opacity-50"><?php the_excerpt(); ?></div>
            </div>
        <?php } ?>


    </div>
</header>
