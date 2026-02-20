<?php

if (has_post_thumbnail()) {
    $header_padding = 'md:pb-14';
} else {
    $header_padding = 'pb-6 md:pb-14';
}

?>

<!-- Hero Section -->
<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden <?php echo $header_padding; ?>">
    <div class="container">


        <!-- Main flex container to align image and text horizontally -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8 md:gap-12">


            <!-- Thumbnail (Moved to Left and Made Circular) -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 md:w-48 md:h-48 rounded-full overflow-hidden border-4 border-black shadow-black-offset">

                    <?php if (get_current_user_id() == $args['buyer_id']): ?>
                    <a href="<?php echo site_url('/account'); ?>">
                    <?php endif; ?>

                    <img class="h-full w-full object-cover"
                         src="<?php echo $args['profile_image']['url']; ?>"
                         alt="<?php echo $args['display_name']; ?>" />

                    <?php if (get_current_user_id() == $args['buyer_id']): ?>
                    </a>
                    <?php endif; ?>

                </div>
            </div>


            <!-- Headings -->
            <div class="text-left">


                <!-- Title -->
                <h1 class="font-bold text-32 md:text-50 mb-4"><?php echo $args['display_name']; ?></h1>

                <!-- Position and org -->
                <?php if (!empty($args['position']) && !empty($args['organization'])) : ?>
                <div class="flex items-center gap-4 font-bold mb-4">
                    <!--<span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"></span>-->
                    <span class="text-20 uppercase text-brown-dark-1 opacity-50"><?php echo $args['position'] . ' at ' . $args['organization']; ?></span>
                </div>
                <?php endif; ?>

                <!-- Rating Stars -->
                <div id="hero-average-rating" class="flex gap-x-1 text-yellow w-32 mb-4" hx-swap-oob="outerHTML">
                    <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => 0 ]); ?>
                </div>


            </div>

        </div>

    </div>
</header>
