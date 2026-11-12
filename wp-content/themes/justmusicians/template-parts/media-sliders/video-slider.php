
<div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden mb-[21px]" x-show="showVideoTab" x-cloak
    x-data="Object.assign(
        youTubeIframeContainerData(),
        videoSliderData({
            totalSlides: <?php if ($args['is_preview']) { echo 'youtubeVideoData.length'; } else { echo (count($args['youtube_video_data'])); } ?>,
            videoData:   <?php if ($args['is_preview']) { echo 'youtubeVideoData';        } else { echo clean_arr_for_doublequotes($args['youtube_video_data']); } ?>,
        })
    )"
    x-on:mouseleave="_leaveSlider()"
    x-on:mouseenter="_enterSlider()"
    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
    x-on:pause-all-youtube-players="_pauseAllPlayers()"
    x-on:pause-youtube-player="_pausePlayerById($event.detail.playerId)"
    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
    x-on:mute-youtube-players="_toggleMute()"
    <?php if ($args['is_preview']) { ?>x-init="$watch('youtubeVideoData', value => _updateVideoSliderData(value) ); _setupVisibilityListener()"
    <?php } else { ?> x-init="_setupVisibilityListener()" <?php } ?>
>

    <div class="aspect-video flex transition-transform duration-500 ease-in-out w-full h-full"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
        x-on:transitionstart="_pausePreviousSlide();">

        <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
            <div class="relative aspect-video w-full h-full object-cover shrink-0"
                x-id="['playerId']"
                x-init="$nextTick(() => {
                    // attempt init all videos as soon as the page loads (youtube-iframe-scripts throttles the init for page load speed optimization)
                    playerIds[index] = $id('playerId');
                    $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData })
                })"
            >
                <!-- checking playerState so that when state is UNSTARTED (-1) but not undefined (before user hits play), the spinner will appear instead of youtube's black screen; this happens when video start_time is not 0 -->
                <div class="absolute inset-0 flex justify-center items-center h-full"
                    :class="{'hidden': $id('playerId') in players &&
                        players[$id('playerId')].isReady &&
                        players[$id('playerId')].playerState != -1
                    }"
                >
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'white']); ?>
                </div>
                <div x-bind:id="$id('playerId')" class="aspect-video w-full h-full object-cover"></div>
            </div>
        </template>

    </div>

    <!-- Video player buttons -->
    <?php get_template_part('template-parts/media-sliders/components/arrow-buttons', '', []); ?>

</div>
