<div class="bg-yellow-light w-full sm:w-56 shrink-0 relative max-w-3xl overflow-hidden"
    x-data="{ hasVideo: youtubeVideoData }"
>

    <!-- Added group, cursor-pointer, and click handler to the entire block -->
    <div class="bg-yellow-light aspect-4/3 flex relative group cursor-pointer">

        <!-- Thumbnail image -->
        <img class="w-auto h-full object-cover aspect-[4/3]" width="400" height="300"
            x-bind:src="pThumbnailSrc || '<?php echo $args['thumbnail_url']; ?>'"
            fetchpriority="high"
        />

        <?php get_template_part('template-parts/media-sliders/components/play-button', '', []); ?>
    </div>

</div>
