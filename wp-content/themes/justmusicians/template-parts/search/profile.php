
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
    if (count($args['youtube_video_ids']) > 0) { ?>

        <div class="w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
            x-data='{
                previousIndex: 0,
                currentIndex: 0,
                totalSlides: <?php echo (count($args['youtube_video_ids']) + 1); ?>,
                videoIds: [null, ...<?php echo json_encode($args['youtube_video_ids']); ?>], // add a null as the first element to correspond to the thumbnail image as the first slide
                pauseVideoById(videoId) {
                    if (videoId) {
                      $dispatch("pause-youtube-player", {"videoId": videoId});
                    }
                },
                playVideoById(videoId) {
                    if (videoId) {
                        $dispatch("play-youtube-player", {"videoId": videoId});
                    }
                },
                updateIndex(newIndex) {
                    this.previousIndex = this.currentIndex; // Save the previous index before updating
                    this.currentIndex = newIndex;            // Update to the new index
                },
            }'
            x-init="() => {
                document.addEventListener('youtube-api-ready', () => {
                    videoIds.forEach(videoId => {
                        if (videoId) { $dispatch('init-youtube-player', {'videoId': videoId}); }
                    });
                }, {once: true});
                if (typeof YT!= 'undefined') {
                    $dispatch('youtube-api-ready');
                    //document.dispatchEvent(new Event('youtube-api-ready'));
                }
            };"
            >
            <div class="bg-yellow-light aspect-4/3 flex transition-transform duration-500 ease-in-out"
                :style="`transform: translateX(-${currentIndex * 100}%)`"
                @transitionend="pauseVideoById(videoIds[previousIndex]); playVideoById(videoIds[currentIndex]);">
                <img <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} ?> class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />

                <?php foreach($args['youtube_video_ids'] as $video_id) { ?>
                    <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover"
                        x-on:mouseout="pauseVideoById(videoIds[currentIndex])"
                        x-on:mouseenter="playVideoById(videoIds[currentIndex])">
                        <iframe id="<?php echo $video_id; ?>"
                            class="aspect-4/3 w-full h-full object-cover"
                            src="https://www.youtube.com/embed/<?php echo $video_id; ?>?enablejsapi=1&controls=0&origin=<?php echo site_url(); ?>"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                    </div>

                <!--
                    <div class="bg-yellow-light aspect-4/3 w-full h-full object-cover"
                        x-on:mouseout="pauseVideoById(videoIds[currentIndex])"
                        x-on:mouseenter="playVideoById(videoIds[currentIndex])">
                        <div id="<?php echo $video_id; ?>" class="w-full h-full object-cover"></div>
                    </div>
                -->

                <?php } ?>

            </div>
            <div class="absolute top-1/2 w-full flex justify-between transform -translate-y-1/2 px-4">
                <div class="absolute top-1/2 transform -translate-y-1/2 left-4">
                    <button type="button" class="bg-black text-white p-2 rounded-full text-lg"
                        @click="updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
                        x-on:mouseout="playVideoById(videoIds[currentIndex])"
                    > &#10094; </button>
                </div>
                <div class="absolute top-1/2 transform -translate-y-1/2 right-4">
                    <button type="button" class="bg-black text-white p-2 rounded-full text-lg"
                        @click="updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
                        x-on:mouseout="playVideoById(videoIds[currentIndex])"
                    > &#10095; </button>
                </div>
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
        <h2 class="text-22 font-bold"><a href="#"><?php echo $args['name']; ?></a></h2>
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
            <?php if (!empty($args['instagram_url'])) { ?>
                <a target="_blank" href="<?php echo $args['instagram_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/instagram.svg'; ?>" /></a>
            <?php } if (!empty($args['facebook_url'])) { ?>
                <a target="_blank" href="<?php echo $args['facebook_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/facebook.svg'; ?>" /></a>
            <?php } if (!empty($args['youtube_url'])) { ?>
                <a target="_blank" href="<?php echo $args['youtube_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/youtube.svg'; ?>" /></a>
            <?php } ?>
        </div>
    </div>

    <!--<button type="button" class="sm:absolute sm:right-3 sm:bottom-3 w-full sm:w-fit hover:bg-yellow-light bg-yellow px-3 py-4 rounded-sm font-sun-motter text-12 inline-block">Send Inquiry</button>-->
</div>
