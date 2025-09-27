
<section class="bg-white pr-6 py-8 lg:pr-8">
    <figure class="max-w-2xl flex flex-col gap-4">


        <!-- Author -->
        <figcaption class="flex gap-x-6">
            <img src="<?php echo $args['author_image_url']; ?>" alt="" class="w-12 h-12 rounded-full" />
            <div class="text-16 flex flex-col gap-1 justify-center items-left">
                <div class="font-semibold text-grey"><?php echo $args['author_name']; ?></div>
                <div class="text-grey"><?php echo implode(' at ', array_filter([$args['author_position'], $args['author_organization']])); ?></div>
            </div>
        </figcaption>

        <!-- Stars -->
        <div class="flex gap-x-1 text-yellow w-24 sm:w-32">
            <?php echo get_template_part('template-parts/reviews/rating-stars', '', [ 'rating' => $args['rating'], ]); ?>
        </div>

        <!-- Review -->
        <blockquote class="text-16 leading-8 font-semibold tracking-tight text-grey">
            <p>“<?php echo $args['review']; ?>”</p>
        </blockquote>


        <!-- Expand Button (perhaps needed to show a long review or to show responses -->
        <!--<button type="button" class="mt-[-2] text-left font-semibold text-grey text-16">Read full review</button>-->


    </figure>
</section>
