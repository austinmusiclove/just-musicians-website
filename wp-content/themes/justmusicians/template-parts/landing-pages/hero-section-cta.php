<section class="bg-brown-light-3 pt-12 md:pt-24 pb-16 md:pb-28 relative">
    <div class="container relative">
        <div class="max-w-3xl mx-auto text-center">
            <div class="flex justify-center">
                <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $args['breadcrumb_items'] ]); ?>
            </div>

            <h2 class="font-sun-motter text-navy text-28 md:text-40 mb-4"><?php echo $args['heading']; ?></h2>
            <p class="text-16 md:text-20 text-brown-dark-3 mb-8 md:mb-12 max-w-2xl mx-auto"><?php echo $args['description']; ?></p>

            <a
                href="<?php echo $args['cta_url']; ?>"
                class="inline-block bg-yellow hover:bg-white text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-6 md:px-8 py-3"
            >
                <?php echo $args['cta_text']; ?>
            </a>
        </div>
    </div>
</section>
