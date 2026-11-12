<div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
    x-on:click="
        _updateVideoData(<?php echo clean_arr_for_doublequotes($args['youtube_video_data']); ?>, <?php echo clean_str_for_doublequotes($args['post_id']); ?>);
        $nextTick(() => { _openMediaModal(); });
    "
>
    <div class="bg-yellow-light aspect-4/3 flex">

        <!-- Thumbnail -->
        <img class="w-auto h-full object-cover aspect-[4/3]" width="400" height="300"
            src="<?php echo $args['thumbnail_url']; ?>"
            alt="Thumbnail image for <?php echo $args['name']; ?>"
            <?php if ($args['lazyload_thumbnail']) { echo 'loading="lazy"';} else { echo 'fetchpriority="high"'; } ?>
        />

    </div>

</div>
