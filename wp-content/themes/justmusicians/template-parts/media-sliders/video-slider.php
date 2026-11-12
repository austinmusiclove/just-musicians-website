
<div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden mb-[21px]" x-show="showVideoTab" x-cloak
    x-data='{
        ...youTubeIframeContainerData(),
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: <?php if ($args['is_preview']) { echo 'youtubeVideoData.length'; } else { echo (count($args['youtube_video_data'])); } ?>,
        videoData:   <?php if ($args['is_preview']) { echo 'youtubeVideoData';        } else { echo clean_arr_for_doublequotes($args['youtube_video_data']); } ?>,
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
            this.totalSlides = videoData.length;
        },
    }'
    x-on:mouseleave="_leaveSlider()"
    x-on:mouseenter="_enterSlider()"
    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
    x-on:pause-all-youtube-players="_pauseAllPlayers()"
    x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
    x-on:mute-youtube-players="_toggleMute()"
    <?php if ($args['is_preview']) { ?>x-init="$watch('youtubeVideoData', value => _updateVideoData(value) ); _setupVisibilityListener()" <?php } ?>
    x-init="_setupVisibilityListener()">

    <div class="bg-yellow-light aspect-video flex transition-transform duration-500 ease-in-out w-full h-full"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
        x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();">

        <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
            <div class="bg-yellow-light aspect-video w-full h-full object-cover"
                x-id="['playerId']"
                x-init="$nextTick(() => { playerIds[index] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData }) })"
            >
                <div class="flex justify-center items-center h-full" :class="{'hidden': $id('playerId') in players && players[$id('playerId')].isReady}">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'white']); ?>
                </div>
                <div x-bind:id="$id('playerId')" class="aspect-video w-full h-full object-cover"></div>
            </div>
        </template>

    </div>


    <!-- Video player buttons -->
    <?php get_template_part('template-parts/media-sliders/components/arrow-buttons', '', []); ?>

</div>
