<?php
/**
 * The template for displaying glossary term pages
 *
 * @package JustMusicians
 */

get_header();

$from_glossary_id = isset($_GET['from_glossary']) ? intval($_GET['from_glossary']) : 0;
$from_glossary = $from_glossary_id ? get_post($from_glossary_id) : null;

$related_terms = get_field('related_terms') ?: [];

?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden <?php echo $header_padding; ?>">

    <!--<img class="w-32 absolute top-0 right-0" src="<?php //echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />-->

    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10">
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6"><?php the_title(); ?></h1>
            <div class="flex items-center gap-4 font-bold mb-8">
                <span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"><?php echo get_the_modified_date(); ?></span>
                <span class="text-20 uppercase text-brown-dark-1 opacity-50"><?php echo the_author_meta('display_name', get_post_field('post_author')); ?></span>
            </div>
        </div>
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
