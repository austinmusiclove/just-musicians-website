
<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    <?php if ($args['last']) { // infinite scroll ?>
    hx-get="/wp-html/v1/listings/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-include="#listing-form"
    <?php } ?>
>

    <button type="button" class="absolute top-7 right-3 opacity-60 hover:opacity-100 hover:scale-105" x-on:click="showFavModal = ! showFavModal">
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>

    <?php
    if (count($args['youtube_player_ids']) > 0) { ?>

        <div class="w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
            x-data='{
                previousIndex: 0,
                currentIndex: 0,
                totalSlides: <?php echo (count($args['youtube_player_ids']) + 1); ?>,
                showArrows: false,
                playerIds: <?php echo json_encode($args['youtube_player_ids']); ?>,
                pausePreviousSlide() {
                    if (this.previousIndex > 0) {
                        $dispatch("pause-youtube-player", {"playerId": this.playerIds[this.previousIndex - 1]});
                    }
                },
                pauseCurrentSlide() {
                    if (this.currentIndex > 0) {
                        $dispatch("pause-youtube-player", {"playerId": this.playerIds[this.currentIndex - 1]});
                    }
                },
                playCurrentSlide() {
                    if (this.currentIndex > 0) {
                        $dispatch("play-youtube-player", {"playerId": this.playerIds[this.currentIndex - 1]});
                    }
                },
                muteAllVideos() { $dispatch("mute-youtube-players"); },
                isPaused() {
                    return this.currentIndex > 0 && players[this.playerIds[this.currentIndex - 1]].isPaused;
                },
                enterSlider() { this.playCurrentSlide(); this.showArrows = true; },
                leaveSlider() { this.pauseCurrentSlide(); this.showArrows = false; },
                updateIndex(newIndex) {
                    this.previousIndex = this.currentIndex; // Save the previous index before updating
                    this.currentIndex = newIndex;            // Update to the new index
                },
            }'
            x-init="() => {
                document.addEventListener('youtube-api-ready', () => {
                    playerIds.forEach((playerId) => {
                        if (playerId) { $dispatch('init-youtube-player', {'playerId': playerId}); }
                    });
                }, {once: true});
                if (typeof YT != 'undefined') {
                    $dispatch('youtube-api-ready');
                }
            };"
            x-on:mouseleave="leaveSlider()"
            x-on:mouseenter="enterSlider()">
            <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
                x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                x-on:transitionstart="pausePreviousSlide(); playCurrentSlide();">
                <img <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?> class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" @click="updateIndex(1)" />

                <?php foreach($args['youtube_player_ids'] as $index=>$player_id) { ?>
                    <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover">
                        <iframe id="<?php echo $player_id; ?>"
                            class="aspect-4/3 w-full h-full object-cover"
                            src="https://www.youtube.com/embed/<?php echo $args['youtube_video_ids'][$index]; ?>?enablejsapi=1&controls=0&mute=1&origin=<?php echo site_url(); ?>"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                    </div>
                <?php } ?>

            </div>


            <!-- Video player buttons -->
            <div class="absolute transform left-2 bottom-2"
                @click="updateIndex(1)"
                x-show="currentIndex == 0">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" />
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                x-show="currentIndex > 0 && isPaused()">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" />
            </div>
            <div class="absolute transform left-2 bottom-2"
                @click="muteAllVideos()"
                x-show="currentIndex > 0 && playersMuted">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" />
            </div>
            <div class="absolute transform left-2 bottom-2"
                @click="muteAllVideos()"
                x-show="currentIndex > 0 && !playersMuted">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" />
            </div>
            <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                @click="updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
                x-show="currentIndex > 0 && showArrows"
                x-transition:enter-start="-translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-full opacity-0" >
                <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                </span>
            </div>
            <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                @click="updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
                x-show="currentIndex < totalSlides - 1 && showArrows"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0" >
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
            </div>


        </div>

    <?php } else {?>

        <div class="w-full sm:w-56 shrink-0">
            <div class="bg-yellow-light aspect-4/3">
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />
            </div>
        </div>

    <?php } ?>

    <div class="py-2 flex flex-col gap-y-2">
        <div class="flex flex-row">
            <h2 class="text-22 font-bold"><a href="#"><?php echo $args['name']; ?></a></h2>
            <?php if ($args['verified']) { ?>
                <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
            <?php } ?>
        </div>
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <?php echo $args['location']; ?>
        </span>
        <p class="text-14"><?php echo $args['description']; ?></p>
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach((array) $args['genres'] as $genre) { ?>
            <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light hover:bg-yellow cursor-pointer inline-block"><?php echo $genre; ?></span><?php
            } ?>
        </div>
        <div class="flex items-center gap-1">
            <?php if (!empty($args['website'])) { ?>
                <a target="_blank" href="<?php echo $args['website']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Website.svg'; ?>" /></a>
            <?php } if (!empty($args['instagram_url'])) { ?>
                <a target="_blank" href="<?php echo $args['instagram_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" /></a>
            <?php } if (!empty($args['facebook_url'])) { ?>
                <a target="_blank" href="<?php echo $args['facebook_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" /></a>
            <?php } if (!empty($args['youtube_url'])) { ?>
                <a target="_blank" href="<?php echo $args['youtube_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" /></a>
            <?php } if (!empty($args['x_url'])) { ?>
                <a target="_blank" href="<?php echo $args['x_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_X.svg'; ?>" /></a>
            <?php } if (!empty($args['tiktok_url'])) { ?>
                <a target="_blank" href="<?php echo $args['tiktok_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" /></a>
            <?php } if (!empty($args['bandcamp_url'])) { ?>
                <a target="_blank" href="<?php echo $args['bandcamp_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Bandcamp.svg'; ?>" /></a>
            <?php } if (!empty($args['spotify_artist_url'])) { ?>
                <a target="_blank" href="<?php echo $args['spotify_artist_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" /></a>
            <?php } if (!empty($args['apple_music_artist_url'])) { ?>
                <a target="_blank" href="<?php echo $args['apple_music_artist_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_AppleMusic.svg'; ?>" /></a>
            <?php } if (!empty($args['soundcloud_url'])) { ?>
                <a target="_blank" href="<?php echo $args['soundcloud_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Soundcloud.svg'; ?>" /></a>
            <?php } ?>
        </div>
    </div>

    <!--<button type="button" class="sm:absolute sm:right-3 sm:bottom-3 w-full sm:w-fit hover:bg-yellow-light bg-yellow px-3 py-4 rounded-sm font-sun-motter text-12 inline-block">Send Inquiry</button>-->
</div>
