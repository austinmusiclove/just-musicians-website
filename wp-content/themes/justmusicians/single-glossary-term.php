<?php
/**
 * The template for displaying glossary term pages
 *
 * @package JustMusicians
 */

get_header();

$default_parent   = get_field('default_parent');
$from_glossary_id = isset($_GET['from_glossary']) ? intval($_GET['from_glossary']) : $default_parent;
$from_glossary    = $from_glossary_id ? get_post($from_glossary_id) : null;

$related_terms = get_field('related_terms') ?: [];

?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden">

    <!--<img class="w-32 absolute top-0 right-0" src="<?php //echo get_template_directory_uri() . '/lib/images/other/violator-blog.svg'; ?>" />-->

    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10">
        <div class="sm:col-span-7 pr-8 sm:pr-0">

            <div class="inline-flex items-center gap-1 mb-4">
                <?php if ($from_glossary) : ?>
                    <a href="<?php echo get_permalink($from_glossary_id); ?>" class="hover:underline">
                        <span><?php echo esc_html($from_glossary->post_title); ?></span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo get_post_type_archive_link('glossary'); ?>" class="hover:underline">
                        <span>Back to Glossaries</span>
                    </a>
                <?php endif; ?>
                <img class="h-8 rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left.svg'; ?>" />
                <span class="font-bold"><?php the_title(); ?></span>
            </div>

            <h1 class="font-bold text-32 md:text-40 mb-6"><?php the_title(); ?></h1>
            <div class="flex items-center gap-4 font-bold mb-8">
                <span class="text-16 px-2 py-0.5 rounded-full bg-yellow inline-block"><?php echo get_the_modified_date(); ?></span>
                <!--<span class="text-20 uppercase text-brown-dark-1 opacity-50"><?php //echo the_author_meta('display_name', get_post_field('post_author')); ?></span>-->
            </div>
        </div>
    </div>

</header>


<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">

    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">

        <?php the_content(); ?>

        <div class="text-20 uppercase text-brown-dark-1 opacity-50 mt-4 font-bold">By <?php echo the_author_meta('display_name', get_post_field('post_author')); ?></div>

        <?php if ($related_terms) : ?>
            <div class="mt-12 pt-8 border-t border-grey">
                <h2 class="font-bold text-xl mb-4">Related Terms</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($related_terms as $term_id) :
                        $term_link = get_permalink($term_id);
                        $term_title = get_the_title($term_id);
                        $glossary_url = $from_glossary_id ? add_query_arg('from_glossary', $from_glossary_id, $term_link) : $term_link;
                    ?>
                        <a href="<?php echo esc_url($glossary_url); ?>" class="pr-3 py-1 hover:underline rounded text-sm">
                            <?php echo esc_html($term_title); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
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
