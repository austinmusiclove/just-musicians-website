<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    x-data="{
        proposal_status:  '<?php echo clean_str_for_doublequotes($args['proposal_status']); ?>',
        proposal_quote:   '<?php echo clean_str_for_doublequotes($args['proposal_quote']); ?>',
        proposal_draw:    '<?php echo clean_str_for_doublequotes($args['proposal_draw']); ?>',
        proposal_details: '<?php echo clean_str_for_doublequotes($args['proposal_details']); ?>',
        proposal_availability: '<?php echo clean_str_for_doublequotes($args['proposal_availability']); ?>',
    }"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
        hx-get="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/applicants/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-indicator="#applicants-spinner"
        hx-swap="beforeend"
    <?php } ?>
>


    <div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
        x-data="{
            previousIndex: 0,
            currentIndex: 0,
            showArrows: isTouchDevice,
            totalSlides: <?php echo (count($args['youtube_video_data']) + 1); ?>,
            videoData:   <?php echo clean_arr_for_doublequotes($args['youtube_video_data']); ?>,
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
        }"
        x-on:mouseleave="_leaveSlider()"
        x-on:mouseenter="_enterSlider()">
        <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
            x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
            x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();"
        >

            <!-- Thumbnail -->
            <img class="w-auto h-full object-cover"
                <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?>
                src="<?php echo $args['thumbnail_url']; ?>"
                x-on:click="if (totalSlides > 1) { _updateIndex(1) }"
            />

            <!-- Youtube video iframes -->
            <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
                <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover"
                    x-id="['playerId']"
                    x-intersect.once="$nextTick(() => { playerIds[index+1] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData }); })"
                    x-intersect:leave="_pauseCurrentSlide()"
                >
                    <div class="flex justify-center items-center h-full" :class="{'hidden': $id('playerId') in players && players[$id('playerId')].isReady}"><?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'white']); ?></div>
                    <div x-bind:id="$id('playerId')" class="aspect-4/3 w-full h-full object-cover"></div>
                </div>
            </template>

        </div>


        <!-- Video player buttons -->
        <!-- Play -->
        <div class="absolute transform left-2 bottom-2"
            @click="_updateIndex(1)"
            x-show="currentIndex == 0 && totalSlides > 1" x-cloak>
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" />
        </div>
        <!-- Pause -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
            x-show="currentIndex > 0 && _isPaused()" x-cloak>
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" />
        </div>
        <!-- Mute -->
        <div class="absolute transform left-2 bottom-2"
            @click="_toggleMuteAllVideos()"
            x-show="currentIndex > 0 && playersMuted" x-cloak>
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" />
        </div>
        <!-- Unmute -->
        <div class="absolute transform left-2 bottom-2"
            @click="_toggleMuteAllVideos()"
            x-show="currentIndex > 0 && !playersMuted" x-cloak>
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" />
        </div>
        <!-- Left Arrow -->
        <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
            @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
            x-show="currentIndex > 0 && showArrows" x-cloak
            x-transition:enter-start="-translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0" >
            <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
        </div>
        <!-- Right Arrow -->
        <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
            @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
            x-show="currentIndex < totalSlides - 1 && showArrows" x-cloak
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0" >
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
        </div>

    </div>


    <div class="py-2 flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <div class="flex flex-row justify-start items-center w-full">
                <!-- Name and verification badge -->
                <?php get_template_part('template-parts/cards/card-components/listing-name', '', [
                    'is_preview' => false,
                    'name'       => $args['name'],
                    'permalink'  => $args['permalink'],
                    'verified'   => $args['verified'],
                ]); ?>

                <!-- Collections button -->
                <?php get_template_part('template-parts/cards/card-components/favorites-button', '', [
                    'post_id'       => $args['listing_id'],
                ]); ?>
            </div>

            <!-- Status -->
            <?php get_template_part('template-parts/cards/card-components/applicant-status-badge', '', ['var' => 'proposal_status']); ?>

        </div>

        <!-- Rating -->
        <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
            'rating'       => empty($args['rating'])       ? 0 : $args['rating'],
            'review_count' => empty($args['review_count']) ? 0 : $args['review_count'],
        ]); ?>

        <!-- Location -->
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
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

        <!-- Details -->
        <div class="flex flex-col" x-show="proposal_details" x-cloak
            x-data="{ expanded: false, tooLong: <?php echo mb_strlen($args['proposal_details']) > 200 ? 'true' : 'false'; ?> }">
            <span class="text-12 text-black/50 font-semibold">Response</span>
            <p class="text-14 whitespace-pre-wrap" x-text="expanded ? proposal_details : proposal_details.slice(0, 200) + (tooLong ? '...' : '')"></p>
            <button x-show="tooLong" x-on:click="expanded = !expanded" class="text-12 underline cursor-pointer w-fit mt-1" x-text="expanded ? 'Show less' : 'Show more'"></button>
        </div>

        <!-- Availability -->
        <div class="flex items-center gap-2" x-show="proposal_availability" x-cloak>
            <span class="text-12 text-black/50 whitespace-nowrap">Availability last updated <?php echo esc_html($args['proposal_updated']); ?></span>
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-2">
            <!-- Quote / Draw -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Quote: $${proposal_quote}`" x-show="proposal_quote" x-cloak></span>
                <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Draw Estimate: ${proposal_draw}`" x-show="proposal_draw" x-cloak></span>
            </div>

            <!-- Send Message -->
            <button type="button" class="w-full sm:w-fit bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 whitespace-nowrap">
                Send Message
            </button>
        </div>

    </div>

</div>
