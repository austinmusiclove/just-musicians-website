
<div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden mb-[21px]" x-show="showVideoTab" x-cloak
    x-data='{
        players: {},
        playersMuted: true,
        playersPaused: false,
        _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
        _pauseAllPlayers()             { pauseAllPlayers(this); },
        _pausePlayer(playerId)         { pausePlayer(this, playerId); },
        _playPlayer(playerId)          { playPlayer(this, playerId); },
        _toggleMute()                  { toggleMute(this); },
        _setupVisibilityListener()     { setupVisibilityListener(this); },
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
                <div class="flex justify-center items-center h-full" :class="{'hidden': $id('playerId') in players && players[$id('playerId')].isReady}"><?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'white']); ?></div>
                <div x-bind:id="$id('playerId')" class="aspect-video w-full h-full object-cover"></div>
            </div>
        </template>

    </div>


    <!-- Video player buttons --> <!-- Pause -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
        x-show="_isPaused()">
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" />
    </div>
    <!-- Mute -->
    <div class="absolute transform left-2 bottom-2"
        @click="_toggleMuteAllVideos()"
        x-show="playersMuted">
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" />
    </div>
    <!-- Unmute -->
    <div class="absolute transform left-2 bottom-2"
        @click="_toggleMuteAllVideos()"
        x-show="!playersMuted">
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" />
    </div>
    <!-- Left Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
        x-show="currentIndex > 0 && showArrows"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0" >
        <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
    </div>
    <!-- Right Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
        @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
        x-show="currentIndex < totalSlides - 1 && showArrows"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0" >
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
    </div>
</div>
