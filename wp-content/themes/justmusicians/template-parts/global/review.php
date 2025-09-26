
<section id="reviews" class="bg-white px-6 py-8 lg:px-8 text-center">
    <figure class="mx-auto max-w-2xl flex flex-col items-center">


        <!-- Stars -->
        <div class="flex gap-x-1 text-yellow w-32">
            <?php echo get_template_part('template-parts/global/ratings/rating-stars', '', [ 'rating' => $args['rating'], ]); ?>
        </div>

        <!-- Review -->
        <blockquote class="mt-10 text-xl/8 font-semibold tracking-tight text-grey sm:text-2xl/9">
            <p>“<?php echo $args['review']; ?>”</p>
        </blockquote>

        <!-- Author -->
        <figcaption class="mt-10 flex items-center gap-x-6">
            <img src="<?php echo $args['author_image_url']; ?>" alt="" class="w-16 h-16 rounded-full" />
            <div class="text-sm/6">
                <div class="font-semibold text-grey"><?php echo $args['author']; ?></div>
                <div class="mt-0.5 text-grey"><?php echo $args['author_title']; ?></div>
            </div>
        </figcaption>


    </figure>
</section>
