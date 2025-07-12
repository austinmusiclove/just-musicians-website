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
    <div class="aspect-video flex transition-transform duration-500 ease-in-out w-full"
        x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
    >
        <span class="aspect-video flex w-full">
            <?php if ($args['is_preview'])  { ?>
                <template x-for="data in orderedImageData['listing_images']" :key="data.image_id">
                    <div class="flex justify-center aspect-video w-full h-full object-cover">
                        <img class="h-full" x-bind:src="_getImageData('listing_images', data.image_id)?.url" />
                    </div>
                </template>
                <template x-if="orderedImageData['listing_images'].length == 0">
                    <div class="flex justify-center aspect-video w-full h-full object-cover">
                        <img class="h-full" x-bind:src="pThumbnailSrc || '<?php echo $args['ph_thumbnail']; ?>'" />
                    </div>
                </template>
            <?php } ?>
            <?php if (!$args['is_preview'] and count($args['listing_image_ids']) > 0) {
                foreach ($args['listing_image_ids'] as $image_id) {
                    $img_url = wp_get_attachment_image_url($image_id, 'medium');
                    if ($img_url) { ?>
                        <div class="flex justify-center aspect-video w-full h-full object-cover">
                            <img class="h-full" src="<?php echo esc_url($img_url); ?>" />
                        </div><?php
                    }
                }
            } else if (!$args['is_preview'] and count($args['listing_image_ids']) == 0) { ?>
                <div class="flex justify-center aspect-video w-full h-full object-cover">
                    <img class="h-full" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" />
                </div>
            <?php } ?>
        </span>

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
    <div class="bg-white/90 py-0.5 px-2 rounded-sm absolute top-2 right-2 text-12" x-text="currentIndex+1 + '/' + totalSlides"></div>
</div>
