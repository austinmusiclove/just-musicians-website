<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    x-data="{
        listingId:          '<?php echo clean_str_for_doublequotes($args['post_id']); ?>',
        proposalListingIds:  <?php echo clean_arr_for_doublequotes($args['listings_invited']); ?>,
        proposalSent() { return this.proposalListingIds.includes(this.listingId); },
    }"
>

    <!-- Media Slider -->
    <?php get_template_part('template-parts/media-sliders/card-media-slider', '', [
        'name'               => $args['name'],
        'lazyload_thumbnail' => $args['lazyload_thumbnail'],
        'thumbnail_url'      => $args['thumbnail_url'],
        'youtube_video_data' => $args['youtube_video_data'],
    ]); ?>


    <div class="py-2 flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <!-- Name and verification badge -->
            <?php get_template_part('template-parts/cards/card-components/listing-name', '', [
                'is_preview' => false,
                'name'       => $args['name'],
                'permalink'  => $args['permalink'],
                'verified'   => $args['verified'],
            ]); ?>

            <!-- Collections button -->
            <?php get_template_part('template-parts/cards/card-components/favorites-button', '', [
                'post_id'       => $args['post_id'],
            ]); ?>

        </div>

        <!-- Rating -->
        <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
            'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
            'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
        ]); ?>

        <!-- Location -->
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" width="10" height="17" alt="" />
            <span><?php echo $args['location']; ?></span>
        </span>

        <!-- Description -->
        <p class="text-14">
            <?php echo $args['description']; ?>
        </p>

        <!-- Genres -->
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach ($args['genres'] as $term) { ?>
                <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block">
                    <?php echo $term; ?>
                </span>
            <?php } ?>
        </div>

    </div>

    <!-- Request proposal button -->
    <span class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit sm:min-w-32" x-show="!proposalSent()" x-cloak>
        <?php echo get_template_part('template-parts/cards/card-components/request-proposal-btn', '', [
            'event_id'   => $args['event_id'],
            'listing_id' => $args['post_id'],
            'btn_text'   => 'Send',
        ]); ?>
    </span>
    <span class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit sm:min-w-32" x-show="proposalSent()" x-cloak>
        <?php echo get_template_part('template-parts/cards/card-components/request-proposal-btn-sent', '', [
            'btn_text' => 'Sent',
        ]); ?>
    </span>

</div>
