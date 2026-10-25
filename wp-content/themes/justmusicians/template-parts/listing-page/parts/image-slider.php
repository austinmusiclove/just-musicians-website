<div class="bg-black aspect-video flex items-center justify-center relative overflow-hidden mb-[21px]" x-show="showImageTab" x-cloak
    x-data="{
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: <?php if (!$args['is_preview']) { echo count($args['listing_image_ids']) > 0 ? count($args['listing_image_ids']) : 1; } else { echo "orderedImageData['listing_images'].length > 0 ? orderedImageData['listing_images'].length : 1"; } ?>,
        _updateIndex(newIndex) { this.previousIndex = this.currentIndex; this.currentIndex = newIndex; },
    }"
    <?php if ($args['is_preview']) { ?>x-init="$watch('orderedImageData', value => { totalSlides = orderedImageData['listing_images'].length == 0 ? 1 : orderedImageData['listing_images'].length; _updateIndex(0); })"<?php } ?>
    x-on:mouseleave="showArrows = false;"
    x-on:mouseenter="showArrows = true"
>
    <div class="flex h-full w-full transition-transform duration-500 ease-in-out"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
    >
        <div class="flex h-full w-full flex-shrink-0">
            <?php if ($args['is_preview']) { ?>
                <template x-for="data in orderedImageData['listing_images']" :key="data.image_id">
                    <div class="w-full h-full flex-shrink-0 flex items-center justify-center bg-black">
                        <img class="h-full w-auto object-contain"
                             x-bind:src="_getImageData('listing_images', data.image_id)?.url"
                             width="600" height="450"
                             alt="Image of <?php echo esc_attr($args['name'] ?? ''); ?>" />
                    </div>
                </template>
                <template x-if="orderedImageData['listing_images'].length == 0">
                    <div class="w-full h-full flex-shrink-0 flex items-center justify-center bg-black">
                        <img class="h-full w-auto object-contain"
                             x-bind:src="pThumbnailSrc || '<?php echo esc_url($args['ph_thumbnail']); ?>'"
                             width="600" height="450"
                             alt="Image of <?php echo esc_attr($args['name'] ?? ''); ?>" />
                    </div>
                </template>
            <?php } ?>

            <?php if (!$args['is_preview'] and count($args['listing_image_ids']) > 0) {
                foreach ($args['listing_image_ids'] as $image_id) {
                    $img_src = wp_get_attachment_image_src($image_id, 'medium-small');
                    if ($img_src) {
                        $img_url    = $img_src[0];
                        $img_width  = $img_src[1];
                        $img_height = $img_src[2];
                        ?>
                        <div class="w-full h-full flex-shrink-0 flex items-center justify-center bg-black">
                            <img class="h-full w-auto object-contain"
                                 src="<?php echo esc_url($img_url); ?>"
                                 width="<?php echo esc_attr($img_width); ?>"
                                 height="<?php echo esc_attr($img_height); ?>"
                                 alt="Image of <?php echo esc_attr($args['name'] ?? ''); ?>" />
                        </div><?php
                    }
                }
            } else if (!$args['is_preview'] and count($args['listing_image_ids']) == 0) {
                $thumb_id  = get_post_thumbnail_id(get_the_ID());
                $thumb_src = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'medium-small') : false;
                $thumb_url = $thumb_src ? $thumb_src[0] : get_the_post_thumbnail_url(get_the_ID(), 'medium-small');
                $thumb_width = $thumb_src ? $thumb_src[1] : 600;
                $thumb_height = $thumb_src ? $thumb_src[2] : 450;
                ?>
                <div class="w-full h-full flex-shrink-0 flex items-center justify-center bg-black">
                    <img class="h-full w-auto object-contain"
                         src="<?php echo esc_url($thumb_url); ?>"
                         width="<?php echo esc_attr($thumb_width); ?>"
                         height="<?php echo esc_attr($thumb_height); ?>"
                         alt="Image of <?php echo esc_attr($args['name'] ?? ''); ?>" />
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Left Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out cursor-pointer z-10"
        @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
        x-show="currentIndex > 0 && showArrows"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0" >
        <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" alt="Previous" />
    </div>

    <!-- Right Arrow -->
    <div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out cursor-pointer z-10"
        @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
        x-show="currentIndex < totalSlides - 1 && showArrows"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0" >
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" alt="Next" />
    </div>

    <!-- Gallery Count -->
    <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12 z-10" x-text="currentIndex+1 + '/' + totalSlides"></div>
</div>
