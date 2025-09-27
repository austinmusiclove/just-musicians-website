
<div class="relative bg-white px-8 py-4 text-center overflow-hidden"
    x-data="{
        previousIndex: 0,
        currentIndex: 0,
        totalSlides: <?php echo $args['review_count']; ?>,
        _updateIndex(newIndex)  { updateIndex(this, newIndex); },
    }"
>
    <div class="bg-yellow-light aspect-square sm:aspect-4/3 flex transition-transform duration-500 ease-in-out"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
    >

        <!-- Slides -->
        <?php
        foreach($args['reviews'] as $review) {
            echo get_template_part('template-parts/reviews/listing-review-slide', '', [
                'rating'              => $review['rating'],
                'review'              => $review['review'],
                'author_name'         => $review['author_name'],
                'author_organization' => $review['author_organization'],
                'author_position'     => $review['author_position'],
                'author_image_url'    => $review['author_image_url'],
            ]);
        }
        ?>

    </div>


    <!-- Left Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
        x-show="currentIndex > 0" x-cloak
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0" >
        <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
    </div>
    <!-- Right Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
        x-show="currentIndex < totalSlides - 1" x-cloak
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0" >
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
    </div>

</div>
