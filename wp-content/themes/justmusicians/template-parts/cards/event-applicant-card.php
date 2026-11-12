<div class="py-4 relative flex flex-col sm:flex-row items-stretch gap-3 md:gap-7 relative border-b border-black/20 last:border-none"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
        hx-get="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/applicants/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-target="#applicant-results"
        hx-swap="beforeend"
        hx-indicator="#applicants-spinner-bottom"
        hx-include="#event-applicants-form"
    <?php } ?>
    x-data="{
        proposal_status:       '<?php echo clean_str_for_doublequotes($args['proposal_status']       ?? ''); ?>',
        proposal_quote:        '<?php echo clean_str_for_doublequotes($args['proposal_quote']        ?? ''); ?>',
        proposal_draw:         '<?php echo clean_str_for_doublequotes($args['proposal_draw']         ?? ''); ?>',
        proposal_details:      '<?php echo clean_str_for_doublequotes($args['proposal_details']      ?? ''); ?>',
        proposal_availability: '<?php echo clean_str_for_doublequotes($args['proposal_availability'] ?? ''); ?>',
    }"
>

    <!-- Notifications -->
    <div class="absolute top-2 -left-2 z-[1]"
        hx-post="<?php echo site_url('/wp-html/v1/clear-notification/'); ?>"
        x-bind:hx-trigger="(!has_notifications(notifications, ['inquiry_response', 'inquiry_response_update'], '<?php echo $args['proposal_id']; ?>')) ? 'never-trigger' : 'intersect once'"
        hx-swap="beforeend"
        hx-indicator="#decoy-indicator"
        hx-vals='{"notification_type":"inquiry_response,inquiry_response_update","subject_id": "<?php echo $args['proposal_id']; ?>" }'
    >
        <span id="decoy-indicator"></span>
        <?php get_template_part('template-parts/cards/card-components/event-applicant-notification-badge', '', ['proposal_id' => $args['proposal_id'] ]); ?>
    </div>

    <div class="flex flex-col gap-2 w-full sm:w-56 shrink-0 max-w-3xl">

        <!-- Media Slider -->
        <?php get_template_part('template-parts/cards/card-components/media-modal-thumbnail', '', [
            'post_id'            => $args['listing_id'],
            'name'               => $args['name'],
            'lazyload_thumbnail' => $args['lazyload_thumbnail'],
            'thumbnail_url'      => $args['thumbnail_url'],
            'youtube_video_data' => $args['youtube_video_data'],
        ]);

        $listing_image_urls = $args['listing_image_urls'] ?? [];
        $listing_image_count = count($listing_image_urls);
        if ($listing_image_count >= 1) {
            $media_items = ($listing_image_count == 1) ? array_filter([$args['thumbnail_url'], $listing_image_urls[0]]) : array_slice($listing_image_urls, 0, 2);
        ?>
            <div class="flex gap-2 w-full">
                <?php foreach ($media_items as $img_url) { ?>
                    <img class="w-[calc(50%-.25rem)] aspect-4/3 object-cover bg-yellow-light" width="400" height="300"
                        src="<?php echo esc_url($img_url); ?>"
                        alt="Applicant image for <?php $args['name']; ?>"
                        <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"'; } ?>
                    />
                <?php } ?>
            </div>
        <?php } ?>
    </div>


    <div class="py-2 flex flex-col gap-y-2 w-full">

        <div class="flex flex-col gap-y-2 w-full grow">

            <div class="flex flex-row justify-between items-start w-full">

                <!-- Name and verification badge -->
                <div class="flex flex-row justify-start items-center w-full">
                    <?php get_template_part('template-parts/cards/card-components/listing-name', '', [
                        'is_preview' => false,
                        'name'       => $args['name'],
                        'permalink'  => $args['permalink'],
                        'verified'   => $args['verified'],
                    ]); ?>
                </div>

                <!-- Status -->
                <?php get_template_part('template-parts/cards/card-components/applicant-status-badge', '', ['status_var' => 'proposal_status']); ?>

                <!-- Collections button -->
                <?php get_template_part('template-parts/cards/card-components/favorites-button', '', [ 'post_id' => $args['listing_id'], ]); ?>

            </div>

            <!-- Rating -->
            <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
                'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
                'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
            ]); ?>


            <div class="flex items-center gap-2" x-data="{ cardEnsembleSize: <?php echo clean_arr_for_doublequotes($args['ensemble_size'] ?? []); ?> }">
                <!-- Location -->
                <span class="text-14 flex items-center flex-wrap">
                    <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" width="10" height="17" alt="" />
                    <span><?php echo $args['location']; ?></span>
                </span>
                <span class="text-black/50" x-show="cardEnsembleSize.length > 0" x-cloak>|</span>

                <!-- Ensemble Size  -->
                <span class="flex items-center text-14 gap-2">
                    <?php get_template_part('template-parts/cards/card-components/ensemble-size', '', [
                        'ensemble_size_var'   => 'cardEnsembleSize',
                        'show_any_when_empty' => 'true',
                    ]); ?>
                </span>
            </div>

            <div class="flex items-center gap-2">
                <!-- Description -->
                <p class="text-14 col-start-1 col row-start-2">
                    <?php echo $args['description']; ?>
                </p>
                <span class="text-black/50">|</span>

                <!-- Genres -->
                <div class="flex items-center gap-1 flex-wrap">
                    <?php foreach ($args['genres'] as $term) { ?>
                        <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block">
                            <?php echo $term; ?>
                        </span>
                    <?php } ?>
                </div>
            </div>

            <div class="clear-both flex flex-col gap-2 mb-2 mt-2 border border-black/20 rounded p-4 bg-yellow-10/50"
                x-show="proposal_details || proposal_availability || proposal_status != 'inquiry' || proposal_quote || proposal_draw" x-cloak
            >

                <!-- Details -->
                <div class="flex flex-col" x-show="proposal_details" x-cloak>
                    <span class="text-14 text-black/50 font-semibold mb-1">Response</span>
                    <?php get_template_part('template-parts/cards/card-components/show-more-text', '', [
                        'text_var' => 'proposal_details',
                        'limit'    => 200,
                    ]); ?>
                </div>

                <!-- Availability -->
                <div class="flex flex-col items-start" x-show="proposal_availability" x-cloak>
                    <span class="text-12 text-black/50" x-show="proposal_status != 'stale'" x-cloak>Availability last updated <?php echo esc_html($args['proposal_updated']); ?></span>
                    <span class="text-12 text-black/50" x-show="proposal_status == 'stale'" x-cloak><span class="capitalize" x-text="proposal_availability"></span> as of <?php echo esc_html($args['proposal_updated']); ?></span>
                    <span class="text-12 text-red" x-show="proposal_status == 'stale'" x-cloak>Availability not updated since event date/time changed</span>
                </div>

                <!-- Quote / Draw -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Quote: $${Number(proposal_quote).toLocaleString('en-US', { maximumFractionDigits: 2 })}`" x-show="proposal_quote" x-cloak></span>
                    <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Draw Estimate: ${Number(proposal_draw).toLocaleString('en-US', { maximumFractionDigits: 2 })}`" x-show="proposal_draw" x-cloak></span>
                </div>

            </div>

        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-2">

            <!-- Send Message (only for published listings to avoid sending message to listing with no owner/author) -->
            <?php if ($args['listing_post_status'] == 'publish') { ?>
                <button type="button" class="w-full sm:w-fit bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 whitespace-nowrap"
                    x-on:click="
                        sendMessageListingName = '<?php echo clean_str_for_doublequotes($args['name'] ?? ''); ?>';
                        sendMessageListingId = <?php echo $args['listing_id']; ?>;
                        sendMessageText = '';
                        showSendMessageSuccess = false;
                        $nextTick(() => { showSendMessageModal = true; });
                    "
                >Send Message</button>
            <?php } ?>

        </div>

    </div>

</div>
