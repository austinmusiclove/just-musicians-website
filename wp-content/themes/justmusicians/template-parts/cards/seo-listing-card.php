<?php

$collection_id = isset($args['collection_id']) ? $args['collection_id'] : null;
$ph_thumbnail  = get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp';

?>

<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    <?php if (!is_null($collection_id)) { ?>
        x-show="collectionsMap['<?php echo $collection_id; ?>'].listings.includes('<?php echo $args['post_id']; ?>')" x-cloak
    <?php } ?>
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page
        $req_path = !empty($args['hx-request_path']) ? $args['hx-request_path'] : 'listings'; ?>
        hx-get="<?php echo site_url('/wp-html/v1/' . $req_path . '/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-indicator="#spinner-end"
        hx-swap="beforeend"
        hx-include="#hx-form"
    <?php } ?>
>


    <div class="flex flex-col gap-3 w-full sm:w-56 shrink-0 max-w-3xl">

    <div class="bg-yellow-light w-full shrink-0 relative overflow-hidden"
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

    <?php
    $listing_image_urls = $args['listing_image_urls'] ?? [];
    $listing_image_count = count($listing_image_urls);
    ?>
    <?php if ($listing_image_count >= 1) {
        $media_items = ($listing_image_count == 1)
            ? array_filter([$args['thumbnail_url'], $listing_image_urls[0]])
            : array_slice($listing_image_urls, 0, 2);
        ?>
        <div class="flex gap-3 w-full">
            <?php foreach ($media_items as $img_url) { ?>
                <img class="w-[calc(50%-6px)] aspect-4/3 object-cover bg-yellow-light"
                    <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"'; } ?>
                    src="<?php echo esc_url($img_url); ?>" />
            <?php } ?>
        </div>
    <?php } ?>

    </div>


    <div class="flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <!-- Name and verification badge -->
            <?php get_template_part('template-parts/cards/card-components/listing-name', '', [
                'is_preview' => false,
                'name'       => $args['name'],
                'permalink'  => $args['permalink'],
                'verified'   => $args['verified'],
            ]); ?>

            <!-- Collections button -->
            <?php get_template_part('template-parts/cards/card-components/favorites-button', '', [
                'post_id' => $args['post_id'],
            ]); ?>

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

        <!-- Bio -->
        <?php if (!empty($args['bio'])) { ?>
            <div class="mb-4">
                <?php get_template_part('template-parts/cards/card-components/show-more-text-seo', '', [
                    'text'  => wp_strip_all_tags($args['bio']),
                ]); ?>
            </div>
        <?php } ?>

    </div>

    <!-- Inquire button -->
    <span class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit">
        <?php get_template_part('template-parts/inquiries/inquire-button', '', [
            'post_id'     => $args['post_id'],
            'name'        => $args['name'],
            'disabled'    => false,
            'btn_classes' => 'hover:bg-yellow-light bg-yellow font-sun-motter w-full px-3 py-3 sm:py-2 rounded-sm text-14 sm:text-12 inline-block sm:w-fit',
            'btn_text'    => 'Send Inquiry',
        ]); ?>
    </span>
</div>

<?php
// MusicGroup Schema
echo get_template_part('template-parts/global/schema/music-group-schema', '', [
    'name'        => $args['name'],
    'description' => $args['description'],
    'genre'       => $args['genres'],
    'phone'       => $args['phone'],
    'url'         => $args['permalink'],
    'thumbnail'   => $args['thumbnail_url'],
    'city'        => $args['city'],
    'state'       => $args['state'],
    'zip_code'    => $args['zip_code'] ?? '',
    'rating'      => $args['rating'],
    'review_count'=> $args['review_count'],
    'reviews'     => $args['reviews'] ?? [],
    'videos'      => $args['youtube_video_data'],
    'images'      => $args['listing_image_urls'] ?? [],
    'sameAs'      => array_filter([
        $args['website'] ?? '',
        $args['facebook_url'] ?? '',
        $args['instagram_url'] ?? '',
        $args['x_url'] ?? '',
        $args['youtube_url'] ?? '',
        $args['tiktok_url'] ?? '',
        $args['bandcamp_url'] ?? '',
        $args['spotify_artist_url'] ?? '',
        $args['apple_music_artist_url'] ?? '',
        $args['soundcloud_url'] ?? '',
    ]),
]);
?>
