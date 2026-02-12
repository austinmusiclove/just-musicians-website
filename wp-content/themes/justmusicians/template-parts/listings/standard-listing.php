<?php

$is_preview    = isset($args['instance']) ? $args['instance'] == 'listing-form' : false;
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
        hx-swap="beforeend"
        hx-include="#hx-form"
    <?php } ?>
>


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
            <img class="w-auto h-full object-cover"
                <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?>
                <?php if (!$is_preview) { ?>src="<?php echo $args['thumbnail_url']; ?>"                  <?php } ?>
                <?php if ($is_preview)  { ?>x-bind:src="pThumbnailSrc || '<?php echo $ph_thumbnail; ?>'" <?php } ?>
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


    <div class="py-2 flex flex-col gap-y-2 w-full">

        <div class="flex flex-row justify-between items-center w-full">

            <!-- Name and verification badge -->
            <?php get_template_part('template-parts/listings/parts/listing-name', '', [
                'is_preview' => $is_preview,
                'name'       => $is_preview ? '' : $args['name'],
                'permalink'  => $is_preview ? '' : $args['permalink'],
                'verified'   => $args['verified'],
            ]); ?>

            <!-- Collections button -->
            <?php if (!$is_preview) { get_template_part('template-parts/listings/parts/favorites-button', '', [
                'post_id'       => $args['post_id'],
            ]);} ?>

        </div>

        <!-- Location -->
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <?php if ($is_preview)  { ?> <span x-text="getListingLocation()"></span> <?php } ?>
            <?php if (!$is_preview) { ?> <span><?php echo $args['location']; ?></span> <?php } ?>
        </span>

        <!-- Description -->
        <p class="text-14"
            <?php if ($is_preview)  { ?> x-text="pDescription || 'Description'" <?php } ?>>
            <?php if (!$is_preview) { echo $args['description']; } ?>
        </p>

        <!-- Genres -->
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach ($args['genres'] as $term) { ?>
                <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block"
                    <?php if ($is_preview) { ?> x-show="genresCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?>
                >
                    <?php echo $term; ?>
                </span>
            <?php } ?>
        </div>

        <!-- Rating -->
        <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
            'rating'       => $args['rating']       ?? 0,
            'review_count' => $args['review_count'] ?? 0,
        ]); ?>

        <!-- Links -->
        <?php /*
        get_template_part('template-parts/listings/parts/link-icons', '', [
            'is_preview'             => $is_preview,
            'website'                => $is_preview ? '' : $args['website'],
            'instagram_url'          => $is_preview ? '' : $args['instagram_url'],
            'x_url'                  => $is_preview ? '' : $args['x_url'],
            'tiktok_url'             => $is_preview ? '' : $args['tiktok_url'],
            'facebook_url'           => $is_preview ? '' : $args['facebook_url'],
            'youtube_url'            => $is_preview ? '' : $args['youtube_url'],
            'bandcamp_url'           => $is_preview ? '' : $args['bandcamp_url'],
            'spotify_artist_url'     => $is_preview ? '' : $args['spotify_artist_url'],
            'apple_music_artist_url' => $is_preview ? '' : $args['apple_music_artist_url'],
            'soundcloud_url'         => $is_preview ? '' : $args['soundcloud_url'],
        ]);
        */ ?>
    </div>

    <!-- Inquire button -->
    <?php get_template_part('template-parts/listings/parts/inquire-button', '', [
        'post_id'  => !$is_preview ? $args['post_id'] : '',
        'name'     => !$is_preview ? $args['name'] : '',
        'disabled' => $is_preview,
    ]); ?>
</div>

<?php
// Local Business Schema
if (!$is_preview) {
    echo get_template_part('template-parts/schema/local-business-schema', '', [
        'name'        => $args['name'],
        'description' => $args['description'],
        'website'     => $args['website'],
        'phone'       => $args['phone'],
        'genres'      => $args['genres'],
        'url'         => $args['permalink'],
        'image'       => $args['thumbnail_url'],
        'city'        => $args['city'],
        'state'       => $args['state'],
    ]);
}
?>
