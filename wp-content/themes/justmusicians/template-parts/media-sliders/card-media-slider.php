<div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
    x-data="mediaSliderData({
        totalSlides: <?php echo (count($args['youtube_video_data']) + 1); ?>,
        videoData:   <?php echo clean_arr_for_doublequotes($args['youtube_video_data']); ?>,
    })"
    x-on:mouseleave="_leaveSlider()"
    x-on:mouseenter="_enterSlider()">
    <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
        x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();"
    >

        <!-- Thumbnail -->
        <img class="w-auto h-full object-cover aspect-[4/3]" width="400" height="300"
            src="<?php echo $args['thumbnail_url']; ?>"
            alt="Thumbnail image for <?php echo $args['name']; ?>"
            <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} else { echo 'fetchpriority="high"'; } ?>
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
    <?php get_template_part('template-parts/media-sliders/components/media-slider-buttons', '', []); ?>

</div>
