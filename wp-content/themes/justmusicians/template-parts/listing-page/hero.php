
<?php
$is_preview    = $args['instance'] == 'listing-form';
$ph_thumbnail  = get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp';

if ($args['instance'] == 'listing-form') {
    $theme = [
        'wrapper_class'          => 'py-4',
        'container_class'        => '',
        'title_wrapper'          => 'py-4',
        'availability_wrapper_1' => 'block absolute sm:top-4 sm:right-4',
        'availability_wrapper_2' => 'hidden'
    ];
} else {
    $theme = [
        'wrapper_class'          => 'my-4 lg:my-16',
        'container_class'        => 'container grid lg:grid-cols-2',
        'title_wrapper'          => 'lg:px-16 py-4 lg:py-10',
        'availability_wrapper_1' => 'block lg:hidden absolute top-2 right-2 sm:top-4 sm:right-4',
        'availability_wrapper_2' => 'hidden lg:block'
    ];
}
?>

<section class="<?php echo $theme['wrapper_class']; ?>"
    x-data="{
        collectionsMap: <?php echo clean_arr_for_doublequotes($args['collections_map']); ?>,
        get sortedCollections()                              { return getSortedCollections(this, 0); },
        _showEmptyFavoriteButton(listingId)                  { return showEmptyFavoriteButton(this, listingId); },
        _showFilledFavoriteButton(listingId)                 { return showFilledFavoriteButton(this, listingId); },
        _showEmptyCollectionButton(collectionId, listingId)  { return showEmptyCollectionButton(this, collectionId, listingId); },
        _showFilledCollectionButton(collectionId, listingId) { return showFilledCollectionButton(this, collectionId, listingId); },
    }"
>


    <div class="<?php echo $theme['container_class']; ?>">
        <div class="bg-yellow w-full aspect-4/3 shadow-black-offset border-4 border-black relative">

            <!-- Cover image -->
            <img class="w-full h-full object-cover"
                <?php if (!$is_preview) { ?>src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" <?php } ?>
                <?php if ($is_preview)  { ?>x-bind:src="pThumbnailSrc || '<?php echo $ph_thumbnail; ?>'"            <?php } ?>
                <?php if ($is_preview)  { ?>x-on:click="focusElm('cover-image')"                                    <?php } ?>
            />

            <!-- <div class="<?php echo $theme['availability_wrapper_1']; ?>">
                <div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>
            </div> -->
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-12 items-end <?php echo $theme['title_wrapper']; ?>">

            <!-- <div class="<?php echo $theme['availability_wrapper_2']; ?>">
                <div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>
            </div> -->

            <div class="flex flex-col gap-5 w-full">

                <div class="flex flex-row justify-between">

                    <!-- Name and verified badge -->
                    <div class="flex items-center gap-2"
                        <?php if ($is_preview) { ?> x-on:click="focusElm('performer-name-input')"<?php } ?>
                    >
                        <h1 class="text-32 font-bold" <?php if ($is_preview) { ?> x-text="pName === '' ? 'Performer or Band Name' : pName" <?php } ?> >
                            <?php if (!$is_preview) { echo get_field('name'); } ?>
                        </h1>
                        <?php if (get_field('verified') === true) { ?>
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
                        <?php } ?>
                    </div>

                    <!-- Collections button -->
                    <?php if (!$is_preview) { get_template_part('template-parts/listings/parts/favorites-button', '', [
                        'post_id' => get_the_ID(),
                    ]);} ?>

                </div>

                <!-- Rating -->
                <div id="rating-with-count" hx-swap-oob="outerHTML">
                    <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
                        'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
                        'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
                    ]); ?>
                </div>

                <!-- Description -->
                <p class="text-18"
                    <?php if ($is_preview) { ?> x-on:click="focusElm('description-input')"                  <?php } ?>
                    <?php if ($is_preview) { ?> x-text="pDescription === '' ? 'Description' : pDescription" <?php } ?>
                >
                    <?php if (!$is_preview) { echo get_field('description'); } ?>
                </p>

                <!-- Location -->
                <div class="flex gap-2 items-center"
                    <?php if ($is_preview) { ?> x-on:click="focusElm('city')" <?php } ?>
                >
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                    <span <?php if ($is_preview)  { ?> x-text="getListingLocation() === '' ? 'City, State' : getListingLocation()" <?php } ?>>
                        <?php if (!$is_preview) { echo get_field('city') . ', ' . get_field('state'); } ?>
                    </span>
                </div>

                <!-- Genres -->
                <div class="flex flex-wrap items-center gap-1"
                    <?php if ($is_preview) { ?> x-on:click="focusElm('search-optimization-terms')" <?php } ?>
                >
                    <?php
                    if (!empty($args['genres']) and !is_wp_error($args['genres'])) {
                        foreach ($args['genres'] as $term) { ?>
                            <span class="bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12" <?php if ($is_preview) { ?>x-show="genresCheckboxes.includes('<?php echo $term; ?>')" x-cloak <?php } ?> >
                                <?php echo $term; ?>
                            </span>
                        <?php }
                    } ?>
                </div>

                <!-- Ensemble Sizes -->
                <?php if ((!empty(get_field('ensemble_size')) and is_array(get_field('ensemble_size'))) or $is_preview) { ?>
                <div
                    <?php if ($is_preview) { ?> x-show="ensembleSizeCheckboxes.length > 0" x-cloak <?php } ?>
                    <?php if ($is_preview) { ?> x-on:click="focusElm('ensemble-size-input');" <?php } ?>
                >
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/people.svg'; ?>" />
                        <h4 class="text-16 font-semibold">Ensemble size</h4>
                    </div>
                    <span class="text-14 v"
                        <?php if ($is_preview) { ?> x-text="ensembleSizeCheckboxes.join(', ')" <?php } ?>
                    >
                        <?php if (!$is_preview) { echo implode(', ', get_field('ensemble_size')); } ?>
                    </span>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</section>
