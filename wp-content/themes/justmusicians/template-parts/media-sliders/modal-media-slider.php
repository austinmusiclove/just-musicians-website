<div class="w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showMediaModal" x-cloak>

    <div class="bg-black/60 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="_closeMediaModal()"
    ></div>

    <div class="relative w-full h-auto flex items-center justify-center" style="max-width: 900px;">

        <div class="bg-black w-full aspect-video flex items-center justify-center relative overflow-hidden"
            x-on:mouseleave="_leaveSlider()"
            x-on:mouseenter="_enterSlider()">
            <div class="aspect-video flex transition-transform duration-500 ease-in-out w-full h-full"
                x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                x-on:transitionstart="_pausePreviousSlide();">

                <!-- Youtube video iframes -->
                <template x-for="(videoData, index) in videoData" :key="videoData.video_id + index">
                    <div class="relative aspect-video w-full h-full object-cover shrink-0"
                        x-id="['playerId']"
                        x-intersect.once="$nextTick(() => {
                            // intersect once allows for the player in the next slide to be loaded before switching to it; use intersect.half.once to only load the player of the current slide
                            playerIds[index] = $id('playerId');
                            $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoData': videoData });
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

    </div>

</div>
