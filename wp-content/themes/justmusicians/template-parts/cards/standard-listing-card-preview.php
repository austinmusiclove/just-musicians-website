<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative">

    <!-- Media Slider -->
    <?php get_template_part('template-parts/cards/card-components/media-modal-thumbnail-preview', '', [
        'thumbnail_url' => get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
    ]); ?>

    <div class="flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <!-- Name and verification badge -->
            <?php get_template_part('template-parts/cards/card-components/listing-name-preview', '', [
                'verified' => $args['verified'],
            ]); ?>

            <!-- Collections button -->
            <?php get_template_part('template-parts/cards/card-components/favorites-button-preview', '', []); ?>

        </div>

        <!-- Rating -->
        <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
            'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
            'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
        ]); ?>

        <div class="flex items-center gap-2">
            <!-- Location -->
            <span class="text-14 flex items-center">
                <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" width="10" height="17" alt="" />
                <span x-text="getListingLocation()"></span>
            </span>
            <span class="text-black/50" x-show="ensembleSizeCheckboxes.length > 0" x-cloak >|</span>

            <!-- Ensemble Size  -->
            <span class="flex items-center text-14 gap-2" x-data="{ cardEnsembleSize: <?php echo clean_arr_for_doublequotes($args['ensemble_size'] ?? []); ?> }">
                <?php get_template_part('template-parts/cards/card-components/ensemble-size', '', [ 'ensemble_size_var' => 'ensembleSizeCheckboxes' ]); ?>
            </span>
        </div>

        <!-- Description -->
        <p class="text-14" x-text="pDescription || 'Description'" > </p>

        <!-- Genres -->
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach ($args['genres'] as $term) { ?>
                <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block"
                    x-show="genresCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak
                >
                    <?php echo $term; ?>
                </span>
            <?php } ?>
        </div>

    </div>

    <!-- Inquire button -->
    <span class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit">
        <button type="button" class="hover:bg-yellow-light bg-yellow font-sun-motter w-full px-3 py-3 sm:py-2 rounded-sm text-14 sm:text-12 inline-block sm:w-fit">Send Inquiry</button>
    </span>
</div>
