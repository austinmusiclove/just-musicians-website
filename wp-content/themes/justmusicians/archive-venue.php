<?php
/**
 * The template for displaying all venues
 *
 */

get_header();


?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <img class="w-32 absolute top-0 right-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10"> <!--Look here -->
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6">Browse Venues</h1>
        </div>

    </div>
</header>


<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">


        <?php
        $args = array(
            'post_type'      => 'venue',
            'posts_per_page' => -1, // get all venues
            'orderby'        => 'title',
            'order'          => 'ASC'
        );

        $venue_query = new WP_Query($args);

        if ( $venue_query->have_posts() ) : ?>
            <ul class="venue-list">
                <?php while ( $venue_query->have_posts() ) : $venue_query->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p>No venues found.</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>


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
