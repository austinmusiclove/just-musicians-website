<?php
/**
 * The template for displaying glossary archive
 *
 * @package JustMusicians
 */

get_header();

$glossaries = new WP_Query(array(
    'post_type' => 'glossary',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
));

?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10">
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6">Glossaries</h1>
        </div>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 mb-8 lg:mb-0">

        <?php if ($glossaries->have_posts()) : ?>
            <ul class="space-y-4">
                <?php while ($glossaries->have_posts()) : $glossaries->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" class="block py-2 hover:underline rounded transition">
                            <h2 class="text-22"><?php the_title(); ?></h2>
                            <?php //if (has_excerpt()) : ?>
                                <!--<p class="text-grey mt-2"><?php //the_excerpt(); ?></p>-->
                            <?php //endif; ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>No glossaries found.</p>
        <?php endif; ?>

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
