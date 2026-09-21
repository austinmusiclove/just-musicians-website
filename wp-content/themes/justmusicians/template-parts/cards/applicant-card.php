<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative border-b border-black/20"
    <?php if ($args['last'] and !$args['is_last_page']) { ?>
    hx-get="<?php echo site_url('/wp-html/v1/applications/' . $args['application_id'] . '/applicants/?page=' . $args['next_page']); ?>"
    hx-trigger="revealed once" hx-target="#applicant-results" hx-swap="beforeend"
    hx-indicator="#applicants-spinner-bottom" hx-include="#applicants-form" <?php } ?> x-data="{
        submission_message: '<?php echo clean_str_for_doublequotes($args['submission_message'] ?? ''); ?>',
        submission_updated: '<?php echo clean_str_for_doublequotes($args['submission_updated'] ?? ''); ?>',
    }">

    <!-- Notifications -->
    <div class="absolute top-2 -left-2 z-[1]" hx-post="<?php echo site_url('/wp-html/v1/clear-notification/'); ?>"
        x-bind:hx-trigger="(!has_notification(notifications, 'new_applicant', '<?php echo $args['submission_id']; ?>')) ? 'never-trigger' : 'intersect once'"
        hx-swap="beforeend" hx-indicator="#decoy-indicator"
        hx-vals='{"notification_type":"new_applicant","subject_id": "<?php echo $args['submission_id']; ?>" }'>
        <span id="decoy-indicator"></span>
        <?php get_template_part('template-parts/cards/card-components/applicant-notification-badge', '', ['submission_id' => $args['submission_id'] ]); ?>
    </div>

    <div class="flex flex-col gap-2 w-full sm:w-56 shrink-0 max-w-3xl">

        <div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden" x-data="{
            previousIndex: 0,
            currentIndex: 0,
            showArrows: isTouchDevice,
            totalSlides: <?php echo (count($args['youtube_video_data']) + 1); ?>,
            videoData:   <?php echo clean_arr_for_doublequotes($args['youtube_video_data'] ?? []); ?>,
            playerIds: {},
            _updateIndex(newIndex)  { updateIndex(this, newIndex); },
            _pausePreviousSlide()   { pausePreviousSlide(this); },
            _pauseCurrentSlide()    { pauseCurrentSlide(this); },
            _playCurrentSlide()     { playCurrentSlide(this); },
            _toggleMuteAllVideos()  { toggleMuteAllVideos(this); },
            _isPaused()             { return isPaused(this); },
            _enterSlider()          { enterSlider(this); },
            _leaveSlider()          { leaveSlider(this); },
            _updateVideoData(videoData) {
                this._updateIndex(0);
                this.videoData = videoData;
                this.totalSlides = videoData.length + 1;
            },
        }" x-on:mouseleave="_leaveSlider()" x-on:mouseenter="_enterSlider()">
            <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
                x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();">

                <!-- Thumbnail -->
                <img class="w-auto h-full object-cover"
                    <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?>
                    src="<?php echo $args['thumbnail_url']; ?>" x-on:click="if (totalSlides > 1) { _updateIndex(1) }" />
                <!-- Youtube video iframes -->
                <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
                    <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover" x-id="['playerId']"
                        x-intersect.once="$nextTick(() => { playerIds[index+1] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData }); })"
                        x-intersect:leave="_pauseCurrentSlide()">
                        <div class="flex justify-center items-center h-full"
                            :class="{'hidden': $id('playerId') in players && players[$id('playerId')].isReady}">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'white']); ?>
                        </div>
                        <div x-bind:id="$id('playerId')" class="aspect-4/3 w-full h-full object-cover"></div>
                    </div>
                </template>

            </div>


            <!-- Video player buttons -->
            <!-- Play -->
            <div class="absolute transform left-2 bottom-2" @click="_updateIndex(1)"
                x-show="currentIndex == 0 && totalSlides > 1" x-cloak>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" />
            </div>
            <!-- Pause -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                x-show="currentIndex > 0 && _isPaused()" x-cloak>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" />
            </div>
            <!-- Mute -->
            <div class="absolute transform left-2 bottom-2" @click="_toggleMuteAllVideos()"
                x-show="currentIndex > 0 && playersMuted" x-cloak>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" />
            </div>
            <!-- Unmute -->
            <div class="absolute transform left-2 bottom-2" @click="_toggleMuteAllVideos()"
                x-show="currentIndex > 0 && !playersMuted" x-cloak>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" />
            </div>
            <!-- Left Arrow -->
            <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
                x-show="currentIndex > 0 && showArrows" x-cloak x-transition:enter-start="-translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-full opacity-0">
                <img class="rotate-180"
                    src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
            </div>
            <!-- Right Arrow -->
            <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
                x-show="currentIndex < totalSlides - 1 && showArrows" x-cloak
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
            </div>

        </div>
        <?php
        //$listing_image_urls = $args['listing_image_urls'] ?? [];
        $listing_image_urls = array('https://hiremusicians.com/wp-content/uploads/2025/10/mozworth-live4-400x300.webp', 'https://hiremusicians.com/wp-content/uploads/2025/09/boz20-small-400x300.webp');
        $listing_image_count = count($listing_image_urls);
        ?>
        <?php if ($listing_image_count >= 1) {
        $media_items = ($listing_image_count == 1)
            ? array_filter([$args['thumbnail_url'], $listing_image_urls[0]])
            : array_slice($listing_image_urls, 0, 2);
        ?>
        <div class="flex gap-2 w-full">
            <?php foreach ($media_items as $img_url) { ?>
            <img class="w-[calc(50%-6px)] aspect-4/3 object-cover bg-yellow-light"
                <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"'; } ?>
                src="<?php echo esc_url($img_url); ?>" />
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <div class="py-2 flex flex-col gap-y-2 w-full">

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

            <!-- Collections button -->
            <?php get_template_part('template-parts/cards/card-components/favorites-button', '', [
                'post_id'       => $args['listing_id'],
            ]); ?>

        </div>

        <!-- Rating -->
        <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
            'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
            'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
        ]); ?>

        <div class="flex items-center gap-2">
            <!-- Location -->
            <span class="text-14 flex items-center flex-wrap">
                <img class="h-4 mr-2"
                    src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                <span><?php echo $args['location']; ?></span>
            </span>
            <span class="text-black/50">|</span>
            <!-- Ensemble Size -->
            <span class="flex items-center text-14 gap-2">
                <?php echo get_template_part('template-parts/events/event-details/ensemble-size'); ?>
                <span>1, 4, 6+</span>
            </span>

        </div>


        <!-- Genres -->
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach ($args['genres'] as $term) { ?>
            <span
                class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block">
                <?php echo $term; ?>
            </span>
            <?php } ?>
        </div>

        <!-- Description 
        <p class="text-14">
            <?php echo $args['description']; ?>
        </p>-->



        <!-- Details x-show="submission_message" x-cloak -->
        <div class="flex flex-col bg-yellow-10 border border-black/10 rounded p-2">
            <span class="text-14 text-black/50 font-semibold mb-1 inline-block">Applicant Message</span>
            <?php get_template_part('template-parts/cards/card-components/show-more-text', '', [
                'text_var' => 'submission_message',
                'limit'    => 200,
            ]); ?>
        </div>
        <!-- Dates -->
        <div class="flex flex-col border border-yellow-40 rounded p-2">
            <span class="text-14 text-black/50 font-semibold mb-1 inline-block">Available Dates</span>
            <div class="text-14 text-black/50"><span><b>September</b>
                    20-22</span><span class="mx-1">|</span><span><b>October</b> 4, 8, 10-14, 16-20,
                    21-25</span><span class="mx-1">|</span><span><b>November</b> 4, 8...</span>
                <button type="button" class="text-black/50 underline cursor-pointer w-fit ml-1">Show
                    more</button>
            </div>

            <!-- x-show="tooLong"
        x-on:click="expanded = !expanded" x-text="expanded ? 'Show less' : 'Show more'" -->
        </div>


        <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-2">
            <?php get_template_part('template-parts/inquiries/inquire-button', '', [
                'post_id'     => $args['listing_id'],
                'name'        => $args['name'],
                'disabled'    => false,
                'btn_classes' => 'hover:bg-yellow-light bg-yellow font-sun-motter w-full sm:w-fit px-3 py-2 rounded-sm text-14 sm:text-12 inline-block sm:w-fit',
                'btn_text'    => 'Send Inquiry',
            ]); ?>
            <span class="text-14 text-black/50 ">Application submission last updated <span
                    x-text="submission_updated"></span></span>


        </div>

    </div>

</div>