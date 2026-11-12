<?php
$collection_id = isset($args['collection_id']) ? $args['collection_id'] : null;
?>

<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    <?php if (isset($args['reorderable']) && $args['reorderable']) { ?>
        data-listing-id="<?php echo $args['post_id']; ?>"
        x-sort:item="<?php echo $args['index']; ?>"
    <?php } ?>
    <?php if (!is_null($collection_id)) { ?>
        x-show="collectionsMap['<?php echo $collection_id; ?>'].listings.includes('<?php echo $args['post_id']; ?>')" x-cloak
    <?php } ?>
>
    <?php if (isset($args['reorderable']) && $args['reorderable']) { ?>
        <input type="hidden" name="reorder_ids[]" value="<?php echo $args['post_id']; ?>" />
        <button type="button" class="cursor-grab text-20 leading-none px-1 text-black/40 hover:text-black select-none self-center shrink-0" data-reorder-handle x-sort:handle x-show="reorderMode" x-cloak title="Reorder listing">☰</button>
    <?php } ?>


    <!-- Media Slider -->
    <?php get_template_part('template-parts/cards/card-components/media-modal-thumbnail', '', [
        'post_id'            => $args['post_id'],
        'name'               => $args['name'],
        'lazyload_thumbnail' => $args['lazyload_thumbnail'],
        'thumbnail_url'      => $args['thumbnail_url'],
        'youtube_video_data' => $args['youtube_video_data'],
    ]); ?>


    <div class="flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <!-- Name and verification badge -->
            <?php get_template_part('template-parts/cards/card-components/listing-name', '', [
                'name'       => $args['name'],
                'permalink'  => $args['permalink'],
                'verified'   => $args['verified'],
                'rank'       => $args['rank'] ?? '',
                'position'   => $args['position'] ?? '',
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

        <div class="flex items-center gap-2">
            <!-- Location -->
            <span class="text-14 flex items-center">
                <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" width="10" height="17" alt="" />
                <span><?php echo $args['location']; ?></span>
            </span>
            <?php if ( !empty($args['ensemble_size']) ) { ?> <span class="text-black/50" >|</span> <?php } ?>

            <!-- Ensemble Size  -->
            <span class="flex items-center text-14 gap-2" x-data="{ cardEnsembleSize: <?php echo clean_arr_for_doublequotes($args['ensemble_size'] ?? []); ?> }">
                <?php get_template_part('template-parts/cards/card-components/ensemble-size', '', [ 'ensemble_size' => $args['ensemble_size'] ]); ?>
            </span>
        </div>

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

    <!-- Inquire button -->
    <span class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit">
        <?php get_template_part('template-parts/inquiries/inquire-button', '', [
            'post_id'     => $args['post_id'],
            'name'        => $args['name'],
            'btn_classes' => 'hover:bg-yellow-light bg-yellow font-sun-motter w-full px-3 py-3 sm:py-2 rounded-sm text-14 sm:text-12 inline-block sm:w-fit',
            'btn_text'    => 'Send Inquiry',
        ]); ?>
    </span>
</div>

<?php if (!empty($args['last']) && empty($args['is_last_page'])) { // Load more results; show a button on the last result of the page as long as it is not the final page
    $req_path = !empty($args['hx-request_path']) ? $args['hx-request_path'] : 'listings'; ?>
    <div class="py-6 flex items-center justify-center page-indicator htmx-indicator-flex-replace" id="load-more-<?php echo $args['post_id']; ?>">
        <button type="button" class="hover:bg-yellow-light bg-yellow font-sun-motter cursor-pointer px-6 py-3 rounded-sm text-16 inline-block"
            preload="load-more-revealed-<?php echo $args['post_id']; ?>"
            x-intersect.once="$dispatch('load-more-revealed-<?php echo $args['post_id']; ?>')"
            hx-get="<?php echo site_url('/wp-html/v1/' . $req_path . '/?page=' . $args['next_page']); ?>"
            hx-trigger="click once"
            hx-target="#load-more-<?php echo $args['post_id']; ?>"
            hx-swap="outerHTML"
            hx-include="#hx-form"
            hx-params="not reorder_ids[]"
            hx-sync="closest #hx-form:abort"
            hx-indicator=".page-indicator"
        >
            Load More Results
        </button>
    </div>
<?php } ?>
