
<div class="popup-wrapper pt-28 md:pt-0 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="<?php echo $args['alpine_show_var']; ?>" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="<?php echo $args['alpine_show_var']; ?> = false"
    ></div>

    <div class="bg-white relative p-8 md:p-20 relative w-full h-full md:w-auto md:h-auto flex items-center justify-center" style="max-width: 780px;">

    <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
        x-on:click="<?php echo $args['alpine_show_var']; ?> = false;"
    />

    <!-- Review content -->
    <section class="aspect-square sm:aspect-4/3 w-full h-full object-cover bg-white px-6 py-8 lg:px-8 text-center flex items-center">
        <figure class="mx-auto max-w-2xl flex flex-col items-center">


            <!-- Stars -->
            <div class="flex gap-x-1 text-yellow w-24 sm:w-32">
                <?php echo get_template_part('template-parts/reviews/rating-stars', '', [ 'rating' => $args['rating'], ]); ?>
            </div>

            <!-- Review -->
            <blockquote class="mt-10 text-xl/8 font-semibold tracking-tight text-grey sm:text-2xl/9 line-clamp-6">
                <p>“<?php echo $args['review']; ?>”</p>
            </blockquote>

            <!-- Author -->
            <figcaption class="mt-10 flex items-center gap-x-6">
                <img src="<?php echo $args['author_image_url']; ?>" alt="" class="w-12 h-12 sm:w-16 sm:h-16 rounded-full" />
                <div class="text-16 sm:text-18 leading-6">
                    <div class="font-semibold text-grey"><?php echo $args['author_name']; ?></div>
                    <div class="mt-0.5 text-grey"><?php echo implode(' at ', array_filter([$args['author_position'], $args['author_organization']])); ?></div>
                </div>
            </figcaption>


        </figure>
    </section>


    </div>
</div>
