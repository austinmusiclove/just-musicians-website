<div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
    x-data="{
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: <?php if ($is_preview) { echo 'youtubeVideoData.length + 1'; } else { echo (count($args['youtube_video_data']) + 1); } ?>,
        videoData:   <?php if ($is_preview) { echo 'youtubeVideoData';            } else { echo clean_arr_for_doublequotes($args['youtube_video_data']); } ?>,
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
    <?php if ($is_preview) { ?> x-init="$watch('youtubeVideoData', value => _updateVideoData(value) )" <?php } ?>
    x-on:mouseleave="_leaveSlider()"
    x-on:mouseenter="_enterSlider()">
    <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
        x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();"
    >

        <!-- Thumbnail -->
        <img class="w-auto h-full object-cover aspect-[4/3]" width="400" height="300"
            <?php if (!$is_preview) { ?>src="<?php echo $args['thumbnail_url']; ?>"                        <?php } ?>
            <?php if ($is_preview)  { ?>x-bind:src="pThumbnailSrc || '<?php echo $ph_thumbnail; ?>'"       <?php } ?>
            <?php if (!$is_preview) { ?>alt="Thumbnail image for <?php echo $args['name']; ?>"             <?php } ?>
            <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} else { echo 'fetchpriority="high"'; } ?>
            x-on:click="if (totalSlides > 1) { _updateIndex(1) }"
        />

        <!-- Youtube video iframes -->
        <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
            <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover"
                x-id="['playerId']"
                <?php if ($is_preview) { ?>
                    x-init="$nextTick(() => { playerIds[index+1] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData }) })"
                <?php } else { ?>
                    x-intersect.once="$nextTick(() => { playerIds[index+1] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData }); })"
                <?php } ?>
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
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" width="32" height="32" alt="Play video" />
    </div>
    <!-- Pause -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
        x-show="currentIndex > 0 && _isPaused()" x-cloak>
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" width="32" height="32" alt="Pause video" />
    </div>
    <!-- Mute -->
    <div class="absolute transform left-2 bottom-2"
        @click="_toggleMuteAllVideos()"
        x-show="currentIndex > 0 && playersMuted" x-cloak>
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" width="24" height="24" alt="Mute audio" />
    </div>
    <!-- Unmute -->
    <div class="absolute transform left-2 bottom-2"
        @click="_toggleMuteAllVideos()"
        x-show="currentIndex > 0 && !playersMuted" x-cloak>
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" width="24" height="24" alt="Unmute audio" />
    </div>
    <!-- Left Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
        x-show="currentIndex > 0 && showArrows" x-cloak
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0" >
        <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" width="24" height="24" alt="Previous slide" />
    </div>
    <!-- Right Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
        x-show="currentIndex < totalSlides - 1 && showArrows" x-cloak
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0" >
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" width="24" height="24" alt="Next slide" />
    </div>

</div>
