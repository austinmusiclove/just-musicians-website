<section class="bg-brown-light-3 pt-12 md:pt-24 pb-12 md:pb-24 relative">
    <div class="container relative flex justify-center">
        <div class="max-w-4xl text-center">
            <div class="flex justify-center">
                <?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $args['breadcrumb_items'] ]); ?>
            </div>

            <h2 class="font-sun-motter text-navy text-28 md:text-40 mb-4"><?php echo $args['heading']; ?></h2>
            <p class="text-16 md:text-20 text-brown-dark-3 mx-auto whitespace-pre-wrap"><?php echo $args['description']; ?></p>

        </div>
    </div>
</section>
