<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

$availability_html = '<div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>';
$genres            = get_the_terms(get_the_ID(), 'genre');
$subgenres         = get_the_terms(get_the_ID(), 'subgenre');
$instrumentations  = get_the_terms(get_the_ID(), 'instrumentation');
$settings          = get_the_terms(get_the_ID(), 'setting');
$keywords          = get_the_terms(get_the_ID(), 'keyword');

$venues_combined  = [];
$verified_ids     = get_field('venues_played_verified') ?: [];
$unverified_ids   = get_field('venues_played_unverified') ?: [];
$verified_posts   = array_map('get_post', $verified_ids);
$unverified_posts = array_map('get_post', $unverified_ids);
foreach (array_merge($verified_posts, $unverified_posts) as $venue) {
    if ($venue && $venue->post_type === 'venue') {
        $venues_combined[] = [
            'name'             => get_field('name', $venue->ID),
            'street_address'   => get_field('street_address', $venue->ID),
            'address_locality' => get_field('address_locality', $venue->ID),
            'postal_code'      => get_field('postal_code', $venue->ID),
            'address_region'   => get_field('address_region', $venue->ID),
        ];
    }
}

$youtube_video_urls = get_field('youtube_video_urls');
$youtube_video_ids = get_youtube_video_ids($youtube_video_urls);
?>


<section class="my-4 lg:my-16">

    <div class="container grid lg:grid-cols-2">
        <div class="bg-yellow w-full aspect-4/3 shadow-black-offset border-4 border-black relative">
            <img class="w-full h-full object-cover" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" />
            <!--<div class="block lg:hidden absolute top-2 right-2 sm:top-4 sm:right-4"><div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div></div>-->
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-12 lg:px-16 py-4 lg:py-10 items-end">
            <!--<div class="hidden lg:block"><div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div></div>-->
            <div class="flex flex-col gap-5 w-full">
                <div class="flex items-center gap-2">
                    <h1 class="text-32 font-bold"><?php echo get_field('name'); ?></h1>
                    <?php if (get_field('verified') === true) { ?>
                        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
                    <?php } ?>
                </div>
                <p class="text-18"><?php echo get_field('description'); ?></p>
                <div class="flex gap-2 items-center">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                    <span><?php echo get_field('city') . ', ' . get_field('state'); ?></span>
                </div>
                <div class="flex flex-wrap items-center gap-1">
                    <?php
                    if (!empty($genres) && !is_wp_error($genres)) {
                        foreach ($genres as $genre) { ?>
                            <span class="bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12"><?php echo $genre->name; ?></span>
                        <?php }
                    } ?>
                </div>
                <?php if (!empty(get_field('ensemble_size')) and is_array(get_field('ensemble_size'))) { ?>
                <div>
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/people.svg'; ?>" />
                        <h4 class="text-16 font-semibold">Ensemble size</h4>
                    </div>
                    <span class="text-14 v"><?php echo implode(', ', get_field('ensemble_size')); ?></span>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>

</section>

<section class="container flex flex-col-reverse lg:grid grid-cols-8 gap-8 xl:gap-24 mb-20">
    <div class="col-span-5 flex flex-col gap-8 items-start">
        <?php if (!empty(get_field('bio'))) { ?>
        <div id="biography">
            <h2 class="text-25 font-bold mb-5">Biography</h2>
            <p class="mb-4"><?php echo get_field('bio'); ?></p>
        </div>
        <?php } ?>
        <?php if (!empty($venues_combined)) { ?>
        <div id="venues"> <!-- Start venues -->
            <h2 class="text-22 font-bold mb-5">Venues played</h2>
            <div class="flex items-center gap-2 flex-wrap">
                <?php foreach($venues_combined as $venue) { ?>
                <div class="bg-yellow-light p-2 rounded text-16 flex flex-col items-start gap-0.5">
                    <span class="font-bold"><?php echo $venue['name']; ?></span>
                    <span><?php echo $venue['street_address']; ?><br/><?php echo $venue['address_locality'] . ', ' . $venue['address_region'] . ' ' . $venue['postal_code']; ?></span>
                </div>
                <?php } ?>
            </div>
        </div> <!-- End venues -->
        <?php } ?>
        <!-- Start media -->
        <div class="w-full" x-data="{
            showImageTab: true,
            showVideoTab: false,
            showStagePlotTab: false,
            hasVideo: <?php echo (count($youtube_video_ids) > 0) ? 'true' : 'false'; ?>,
            hideTabs() {
                this.showImageTab = false;
                this.showVideoTab = false;
                this.showStagePlotTab = false;
            },
        }">
            <h2 class="text-25 font-bold mb-5">Media</h2>
            <div class="flex items-start gap-4 media-tabs mb-2.5">
                <div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showImageTab}" x-on:click="hideTabs(); showImageTab = true;">Images</div>
                <div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showVideoTab}" x-show="hasVideo" x-cloak x-on:click="hideTabs(); showVideoTab = true;">Videos</div>
                <!--<div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showStagePlotTab}" x-on:click="hideTabs(); showStagePlotTab = true;">Stage Plots</div>-->
            </div>
            <!-- Image -->
            <div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden" x-show="showImageTab" x-cloak
                x-data="{
                    previousIndex: 0,
                    currentIndex: 0,
                    showArrows: isTouchDevice,
                    totalSlides: 1,
                    _updateIndex(newIndex)  { this.previousIndex = this.currentIndex; this.currentIndex = newIndex; },
                }"
                x-on:mouseleave="showArrows = false;"
                x-on:mouseenter="showArrows = true"
            >
                <div class="aspect-video flex transition-transform duration-500 ease-in-out"
                    x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                >
                    <span class="aspect-video flex items-center justify-center"><img class="h-full" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" /></span>

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
                <!-- Gallery Count -->
                <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12" x-text="currentIndex+1 + '/' + totalSlides">1/6</div>
            </div>
            <!-- Video -->
            <div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden" x-show="showVideoTab" x-cloak
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
                    totalSlides: <?php echo (count($youtube_video_ids)); ?>,
                    videoIds:    <?php echo clean_arr_for_doublequotes($youtube_video_ids); ?>,
                    playerIds: {},
                    _updateIndex(newIndex)  { updateIndex(this, newIndex); },
                    _pausePreviousSlide()   { pausePreviousSlide(this); },
                    _pauseCurrentSlide()    { pauseCurrentSlide(this); },
                    _playCurrentSlide()     { playCurrentSlide(this); },
                    _toggleMuteAllVideos()  { toggleMuteAllVideos(this); },
                    _isPaused()             { return isPaused(this); },
                    _enterSlider()          { enterSlider(this); },
                    _leaveSlider()          { leaveSlider(this); },
                }'
                x-on:mouseleave="_leaveSlider()"
                x-on:mouseenter="_enterSlider()"
                x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoId);"
                x-on:pause-all-youtube-players="_pauseAllPlayers()"
                x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                x-on:mute-youtube-players="_toggleMute()"
                x-init="_setupVisibilityListener()">

                <div class="bg-yellow-light aspect-video flex transition-transform duration-500 ease-in-out w-full h-full"
                    x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                    x-on:transitionstart="_pausePreviousSlide(); _playCurrentSlide();">

                    <template x-for="(videoId, index) in videoIds" :key="videoId + index">
                        <div class="bg-yellow-light aspect-video w-full h-full object-cover"
                            x-id="['playerId']"
                            x-init="$nextTick(() => { playerIds[index] = $id('playerId'); $dispatch('init-youtube-player', { 'playerId': $id('playerId'), 'videoId': videoId }) })"
                        >
                            <div x-bind:id="$id('playerId')" class="aspect-video w-full h-full object-cover"></div>
                        </div>
                    </template>

                </div>


                <!-- Video player buttons -->
                <!-- Pause -->
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
            <div class="bg-black aspect-video flex items-center justify-center relative" x-show="false" x-cloak>
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/S3VOtKRNybg?si=wNTtAB3c-ZpUl0NT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
               <!-- Left Arrow -->
               <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="-translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="-translate-x-full opacity-0" >
                    <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                </div>
                <!-- Right Arrow -->
                <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
                    x-transition:enter-start="translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-full opacity-0" >
                    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" />
                </div>
            </div>
            <!-- Stage Plots -->
            <div class="flex flex-col gap-1" x-show="showStagePlotTab" x-cloak>
                <div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden"
                    x-data="{
                        previousIndex: 0,
                        currentIndex: 0,
                        showArrows: isTouchDevice,
                        totalSlides: 1,
                        _updateIndex(newIndex)  { this.previousIndex = this.currentIndex; this.currentIndex = newIndex; },
                    }"
                    x-on:mouseleave="showArrows = false;"
                    x-on:mouseenter="showArrows = true"
                >
                    <div class="aspect-video flex transition-transform duration-500 ease-in-out"
                        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
                    >
                        <span class="aspect-video flex items-center justify-center"><img class="h-full z-0" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/stage-plot.jpg'; ?>" /></span>

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
                    <!-- Gallery Count -->
                    <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12" x-text="currentIndex+1 + '/' + totalSlides">1/6</div>
                </div>
                 <div class="text-14">Stage plot image  1</div>
            </div>
        </div>
        <!-- Start calendar -->
        <!--
        <div class="w-full">
            <h2 class="text-25 font-bold mb-5">Calendar</h2>
            <div class="border border-black/40 rounded w-full">
                <div class="flex justify-between items-center border-b border-black/40 pl-4 pr-2">
                    <div class="pt-3 sm:pt-4 flex gap-4 sm:gap-6 items-start">
                        <?php
                            $button_class = 'calendar-tab pb-2 flex items-center gap-2 text-14 sm:text-16';
                            $dot_class = 'h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full mx-1 md:mx-1.5';
                        ?>
                        <button class="<?php echo $button_class; ?> active">Show all</button>
                        <button class="<?php echo $button_class; ?>">
                            Available
                            <div class="<?php echo $dot_class; ?>" style="background-color: #F4E5CB"></div>
                        </button>
                        <button class="<?php echo $button_class; ?>">
                            Gig
                            <div class="<?php echo $dot_class; ?>" style="background-color: #D29429"></div>
                        </button>
                        <button class="<?php echo $button_class; ?>">
                            Unavailable
                            <div class="<?php echo $dot_class; ?>" style="background-color: #CCCCCC"></div>
                        </button>
                    </div>
                    <button class="hidden sm:block px-2.5 py-2 border border-black/20 rounded font-bold text-14">Today</button>
                </div>
                <div class="flex flex-row p-8 gap-8">
                    <?php echo get_template_part('template-parts/global/calendar', '', array(
                        'month' => 'April',
                        'year' => '2025',
                        'order' => 1,
                        'responsive' => '',
                        'event_day' => 20
                    )); ?>
                    <?php echo get_template_part('template-parts/global/calendar', '', array(
                        'month' => 'May',
                        'year' => '2025',
                        'order' => 2,
                        'responsive' => 'hidden sm:block',
                        'event_day' => null
                    )); ?>
                </div>
            </div>
        </div>
        -->
        <!-- End calendar -->
    </div>

    <div class="col-span-3">
        <div class="sticky top-20 flex flex-col gap-8">
            <!--<button type="button" data-trigger="quote" class="bg-yellow w-full shadow-black-offset border-2 border-black font-sun-motter text-18 px-2 py-2">Request a Quote</button>-->
            <div class="flex flex-col gap-4">

            <div class="sidebar-module border border-black/40 rounded overflow-hidden">
                    <h3 class="bg-yellow-light-50 font-bold py-2 px-3">Contact Information</h3>
                    <div class="p-4 flex flex-col gap-4">
                        <!-- Start contact info -->
                        <div class="grid grid-cols-2 gap-x-12 gap-y-4 w-full">

                            <?php if (!empty(get_field('phone'))) { ?>
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/phone.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Phone</h4>
                                </div>
                                <?php if (is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('phone'); ?></span><?php } ?>
                                <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>
                            </div>
                            <?php } ?>
                            <?php if (!empty(get_field('email'))) { ?>
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/email.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Email</h4>
                                </div>
                                <?php if (is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block"><?php echo get_field('email'); ?></span><?php } ?>
                                <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>
                            </div>
                            <?php } ?>
                            <?php if (!empty(get_field('website'))) { ?>
                            <div>
                                <div class="flex items-center gap-1">
                                    <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/website.svg'; ?>" />
                                    <h4 class="text-16 font-semibold">Website</h4>
                                </div>
                                <span class="text-14 text-yellow underline cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis block">
                                    <a href="<?php echo get_field('website'); ?>" title="<?php echo get_field('website'); ?>" target="_blank"><?php echo clean_url_for_display(get_field('website')); ?></a>
                                </span>
                            </div>
                            <?php } ?>
                            <?php if (empty(get_field('phone')) and empty(get_field('email')) and empty(get_field('website'))) { ?>
                            <div>
                                <div class="flex items-center gap-1">
                                    <h4 class="text-16 font-semibold">No Contact Info Available</h4>
                                </div>
                            </div>
                            <?php } ?>
                        </div> <!-- End contact info -->


                        <div class="w-full bg-black/20 h-px"></div>
                        <div>
                            <h4 class="text-16 mb-3 font-bold">Socials</h4>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3 w-fit text-14">
                                <?php if (!empty(get_field('instagram_handle'))) { ?>
                                <a class="flex items-center gap-2" href="<?php echo get_field('instagram_url'); ?>" target="_blank">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">@<?php echo get_field('instagram_handle'); ?></span>
                                </a>
                                <?php } ?>
                                <?php if (!empty(get_field('spotify_url'))) { ?>
                                <a class="flex items-center gap-2" href="<?php echo get_field('spotify_url'); ?>" target="_blank">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('name'); ?></span>
                                </a>
                                <?php } ?>
                                <?php if (!empty(get_field('tiktok_url'))) { ?>
                                <a class="flex items-center gap-2" href="<?php echo get_field('tiktok_url'); ?>" target="_blank">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis">@<?php echo get_field('tiktok_handle'); ?></span>
                                </a>
                                <?php } ?>
                                <?php if (!empty(get_field('youtube_url'))) { ?>
                                <a class="flex items-center gap-2" href="<?php echo get_field('youtube_url'); ?>" target="_blank">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo clean_url_for_display(get_field('youtube_url')); ?></span>
                                </a>
                                <?php } ?>
                                <?php if (!empty(get_field('facebook_url'))) { ?>
                                <a class="flex items-center gap-2" href="<?php echo get_field('facebook_url'); ?>" target="_blank">
                                    <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
                                    <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('name'); ?></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div> <!-- End social media -->
                    </div>
                </div> <!-- End sidebar module -->

                <div class="sidebar-module border border-black/40 rounded" x-data="{ showClassifications: false }">
                    <div class="flex items-center justify-between bg-yellow-light-50 font-bold py-2 px-3 cursor-pointer" x-on:click="showClassifications = !showClassifications;">
                        <h3>Classifications</h3>
                        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron.svg'; ?>" />
                    </div>
                    <div class="p-4 flex flex-col gap-4 max-h-96" x-show="showClassifications" x-collapse x-cloak>
                        <?php if (!empty($genres) && !is_wp_error($genres)) { ?>
                        <div>
                            <h4 class="text-16 mb-3">Genres</h4>
                            <div class="flex flex-wrap items-center gap-1">
                                <?php foreach ($genres as $term) { ?>
                                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12"><?php echo $term->name; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if (!empty($subgenres) && !is_wp_error($subgenres)) { ?>
                        <div>
                            <h4 class="text-16 mb-3">Sub-genres</h4>
                            <div class="flex flex-wrap items-center gap-1">
                                <?php foreach ($subgenres as $term) { ?>
                                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12"><?php echo $term->name; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if (!empty($instrumentations) && !is_wp_error($instrumentations)) { ?>
                        <div>
                            <h4 class="text-16 mb-3">Instrumentation</h4>
                            <div class="flex flex-wrap items-center gap-1">
                                <?php foreach ($instrumentations as $term) { ?>
                                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12"><?php echo $term->name; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if (!empty($settings) && !is_wp_error($settings)) { ?>
                        <div>
                            <h4 class="text-16 mb-3">Settings</h4>
                            <div class="flex flex-wrap items-center gap-1">
                                <?php foreach ($settings as $term) { ?>
                                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12"><?php echo $term->name; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if (!empty($keywords) && !is_wp_error($keywords)) { ?>
                        <div>
                            <h4 class="text-16 mb-3">More keywords</h4>
                            <div class="flex flex-wrap items-center gap-1">
                                <?php foreach ($keywords as $term) { ?>
                                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12"><?php echo $term->name; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div> <!-- End sidebar module -->

            </div>
        </div>
    </div>

</section>


<?php
get_footer();

