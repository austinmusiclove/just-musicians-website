<?php
/**
 * The podcast template file
 *
 * @package JustMusicians
 */
get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">

    <!--<img class="w-32 absolute top-0 right-0 z-10" src="<?php echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />-->
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">


        <div class="sm:col-span-3 flex flex-col justify-center">
            <div class="w-full">
                <div class="w-full relative">
                    <?= un_get_featured_image(get_post_thumbnail_id(), 'medium', ['class' => 'h-full w-full object-cover']) ?>
                </div>
            </div>
        </div>
        <div class="sm:col-span-4 flex flex-col gap-y-6 justify-center">
            <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <div class="text-20 uppercase text-brown-dark-1 opacity-50 font-bold">Hosted by John Filippone</div>
        </div>


    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">


        <?php $query = new WP_Query([
            'posts_per_page' => 10,
            'post_type' => 'podcast',
        ]);
        if ($query->have_posts()) :
            while ( $query->have_posts() ) : $query->the_post();
        ?>
            <?php echo get_template_part('template-parts/listings/audio-listing', '', array(
                'name' => get_the_title(),
                'date' => get_the_date(),
                'excerpt' => get_the_excerpt(),
                'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'categories' => ['Music Venues', 'Playlisting'],
                'audio' => get_field('audio'),
            )); ?>
        <?php
            endwhile;
            wp_reset_postdata();
        endif; ?>


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
un_simple_pagination();

get_footer();
