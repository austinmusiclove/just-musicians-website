<?php
$is_preview = !empty($args['is_preview']) ? $args['is_preview'] : false;
?>

<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
    hx-get="/wp-html/v1/listings/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-include="#listing-form"
    <?php } ?>
>

    <?php if (!$is_preview) { ?>
    <button type="button" class="absolute top-7 right-3 opacity-60 hover:opacity-100 hover:scale-105" x-on:click="showFavModal = ! showFavModal">
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>
    <?php } ?>

    <?php
    if (count($args['youtube_video_ids']) > 0 or !empty($args['alpine_video_ids'])) { ?>

        <div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
            x-data="{
                previousIndex: 0,
                currentIndex: 0,
                showArrows: isTouchDevice,
                totalSlides: <?php echo (count($args['youtube_video_ids']) + 1); ?>,
                videoIds:    <?php if (!empty($args['alpine_video_ids'])) { echo $args['alpine_video_ids']; } else { echo clean_arr_for_doublequotes($args['youtube_video_ids']); } ?>,
                playerIds: {},
                _updateIndex(newIndex)  { updateIndex(this, newIndex); },
                _pausePreviousSlide()   { pausePreviousSlide(this); },
                _pauseCurrentSlide()    { pauseCurrentSlide(this); },
                _playCurrentSlide()     { playCurrentSlide(this); },
                _toggleMuteAllVideos()  { toggleMuteAllVideos(this); },
                _isPaused()             { return isPaused(this); },
                _enterSlider()          { enterSlider(this); },
                _leaveSlider()          { leaveSlider(this); },
                _updateVideos(videoIds) {
                    // Slide left if the current slide is the last one and a slide is getting destroyed to avoid user seeing a blank slide
                    if (this.totalSlides > videoIds.length + 1 && this.currentIndex == this.totalSlides -1 ) { this._updateIndex(this.currentIndex-1); }
                    this.videoIds = videoIds;
                    this.totalSlides = videoIds.length + 1;
                },
            }"
            <?php if (!empty($args['alpine_video_ids'])) { ?> x-init="$watch('<?php echo $args['alpine_video_ids']; ?>', value => _updateVideos(value) )" <?php } ?>
            x-on:mouseleave="_leaveSlider()"
            x-on:mouseenter="_enterSlider()">
            <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
                x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();"
            >

                <img class="w-full h-full object-cover"
                    <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?>
                    src="<?php echo $args['thumbnail_url']; ?>" <?php if (!empty($args['alpine_thumbnail_src'])) { echo 'x-bind:src="' . $args['alpine_thumbnail_src'] . '"'; } ?>
                    x-on:click="if (totalSlides > 1) { _updateIndex(1) }"
                />

                <template
                    x-for="(videoId, index) in videoIds"
                    :key="videoId + index"
                >
                    <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover"
                        x-id="['playerId']"
                        <?php if (!empty($args['alpine_video_ids'])) { ?>
                            x-init="$nextTick(() => { playerIds[index] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoId': videoId }) })"
                        <?php } else { ?>
                            x-intersect.once="$nextTick(() => { playerIds[index] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoId': videoId }) })"
                        <?php } ?>
                    >
                        <div x-bind:id="$id('playerId')" class="aspect-4/3 w-full h-full object-cover"
                        ></div>
                    </div>
                </template>

            </div>


            <!-- Video player buttons -->
            <!-- Play -->
            <div class="absolute transform left-2 bottom-2"
                @click="_updateIndex(1)"
                x-show="currentIndex == 0 && totalSlides > 1">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" />
            </div>
            <!-- Pause -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                x-show="currentIndex > 0 && _isPaused()">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" />
            </div>
            <!-- Mute -->
            <div class="absolute transform left-2 bottom-2"
                @click="_toggleMuteAllVideos()"
                x-show="currentIndex > 0 && playersMuted">
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" />
            </div>
            <!-- Unmute -->
            <div class="absolute transform left-2 bottom-2"
                @click="_toggleMuteAllVideos()"
                x-show="currentIndex > 0 && !playersMuted">
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
                </span>
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

    <?php } else {?>

        <div class="w-full sm:w-56 shrink-0">
            <div class="bg-yellow-light aspect-4/3">
            <img class="w-full h-full object-cover"
                <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?>
                src="<?php echo $args['thumbnail_url']; ?>" <?php if (!empty($args['alpine_thumbnail_src'])) { echo 'x-bind:src="' . $args['alpine_thumbnail_src'] . '"'; } ?>
            />
            </div>
        </div>

    <?php } ?>

    <div class="py-2 flex flex-col gap-y-2">

        <!-- Name and verification badge -->
        <div class="flex flex-row">
            <h2 class="text-22 font-bold">
                <a href="#" <?php if (!empty($args['alpine_name'])) { echo 'x-text="' . $args['alpine_name'] . ' === \'\' ? \'' . $args['name'] . '\' : ' . $args['alpine_name'] . '"'; } ?>
                    x-on:click="showArtistPageModal = true"
                >
                    <?php echo $args['name']; ?>
                </a>
            </h2>
            <?php if ($args['verified']) { ?>
                <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
            <?php } ?>
        </div>

        <!-- Location -->
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <span <?php if (!empty($args['alpine_location'])) { echo 'x-text="' . $args['alpine_location'] . ' === \'\' ? \'' . $args['location'] . '\' : ' . $args['alpine_location'] . '"'; } ?>><?php echo $args['location']; ?></span>
        </span>

        <!-- Description -->
        <p class="text-14" <?php if (!empty($args['alpine_description'])) { echo 'x-text="' . $args['alpine_description'] . ' === \'\' ? \'' . $args['description'] . '\' : ' . $args['alpine_description'] . '"'; } ?>><?php echo $args['description']; ?></p>

        <!-- Genres -->
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach ($args['genres'] as $term) { ?>
                <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"
                    x-show="<?php if (!empty($args['alpine_show_genre'])) { echo $args['alpine_show_genre'] . "('" . $term . "')"; } else { echo 'true'; } ?>"
                    x-cloak >
                    <?php echo $term; ?>
                </span>
            <?php } ?>
        </div>

        <!-- Links -->
        <div class="flex items-center gap-1">
            <?php if (!empty($args['website']) or !empty($args['alpine_website'])) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_website'])) { echo 'x-bind:href="' . $args['alpine_website'] . '"'; } else { echo 'href="' . $args['website'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_website'])) { echo $args['alpine_website']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Website.svg'; ?>" />
                </a>
            <?php } if (!empty($args['instagram_url']) or !empty($args['alpine_instagram_url'])) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_instagram_url'])) { echo 'x-bind:href="' . $args['alpine_instagram_url'] . '"'; } else { echo 'href="' . $args['instagram_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_instagram_url'])) { echo $args['alpine_instagram_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
                </a>
            <?php } if (!empty($args['facebook_url'] or !empty($args['alpine_facebook_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_facebook_url'])) { echo 'x-bind:href="' . $args['alpine_facebook_url'] . '"'; } else { echo 'href="' . $args['facebook_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_facebook_url'])) { echo $args['alpine_facebook_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
                </a>
            <?php } if (!empty($args['youtube_url'] or !empty($args['alpine_youtube_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_youtube_url'])) { echo 'x-bind:href="' . $args['alpine_youtube_url'] . '"'; } else { echo 'href="' . $args['youtube_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_youtube_url'])) { echo $args['alpine_youtube_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
                </a>
            <?php } if (!empty($args['x_url'] or !empty($args['alpine_x_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_x_url'])) { echo 'x-bind:href="' . $args['alpine_x_url'] . '"'; } else { echo 'href="' . $args['x_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_x_url'])) { echo $args['alpine_x_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_X.svg'; ?>" />
                </a>
            <?php } if (!empty($args['tiktok_url'] or !empty($args['alpine_tiktok_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_tiktok_url'])) { echo 'x-bind:href="' . $args['alpine_tiktok_url'] . '"'; } else { echo 'href="' . $args['tiktok_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_tiktok_url'])) { echo $args['alpine_tiktok_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
                </a>
            <?php } if (!empty($args['bandcamp_url'] or !empty($args['alpine_bandcamp_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_bandcamp_url'])) { echo 'x-bind:href="' . $args['alpine_bandcamp_url'] . '"'; } else { echo 'href="' . $args['bandcamp_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_bandcamp_url'])) { echo $args['alpine_bandcamp_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Bandcamp.svg'; ?>" />
                </a>
            <?php } if (!empty($args['spotify_artist_url'] or !empty($args['alpine_spotify_artist_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_spotify_artist_url'])) { echo 'x-bind:href="' . $args['alpine_spotify_artist_url'] . '"'; } else { echo 'href="' . $args['spotify_artist_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_spotify_artist_url'])) { echo $args['alpine_spotify_artist_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
                </a>
            <?php } if (!empty($args['apple_music_artist_url'] or !empty($args['alpine_apple_music_artist_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_apple_music_artist_url'])) { echo 'x-bind:href="' . $args['alpine_apple_music_artist_url'] . '"'; } else { echo 'href="' . $args['apple_music_artist_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_apple_music_artist_url'])) { echo $args['alpine_apple_music_artist_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_AppleMusic.svg'; ?>" />
                </a>
            <?php } if (!empty($args['soundcloud_url'] or !empty($args['alpine_soundcloud_url']))) { ?>
                <a target="_blank"
                    <?php if (!empty($args['alpine_soundcloud_url'])) { echo 'x-bind:href="' . $args['alpine_soundcloud_url'] . '"'; } else { echo 'href="' . $args['soundcloud_url'] . '"'; } ?>
                    x-show="<?php if (!empty($args['alpine_soundcloud_url'])) { echo $args['alpine_soundcloud_url']; } else { echo 'true'; } ?>"
                    x-cloak >
                    <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Soundcloud.svg'; ?>" />
                </a>
            <?php } ?>
        </div>
    </div>

    <!--<button type="button" class="sm:absolute sm:right-3 sm:bottom-3 w-full sm:w-fit hover:bg-yellow-light bg-yellow px-3 py-4 rounded-sm font-sun-motter text-12 inline-block">Send Inquiry</button>-->
</div>
