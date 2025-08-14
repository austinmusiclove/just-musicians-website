<span
    x-data="{
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: <?php if (!$args['is_preview']) { echo count($args['stage_plot_ids']); } else { echo "orderedImageData['stage_plots'].length"; } ?>,
        _updateIndex(newIndex)  { this.previousIndex = this.currentIndex; this.currentIndex = newIndex; },
    }"
    <?php if ($args['is_preview']) { ?>x-init="$watch('orderedImageData', value => { totalSlides = orderedImageData['stage_plots'].length == 0 ? 1 : orderedImageData['stage_plots'].length; _updateIndex(0); })"<?php } ?>
    x-on:mouseleave="showArrows = false;"
    x-on:mouseenter="showArrows = true"
>
    <div class="bg-black aspect-video flex flex-col items-center justify-center relative overflow-hidden mb-[21px]" x-show="showStagePlotTab" x-cloak>
        <div class="aspect-video flex transition-transform duration-500 ease-in-out w-full"
            x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
        >
            <span class="aspect-video flex w-full">
                <?php if ($args['is_preview']) { ?>
                    <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                        <div class="flex justify-center aspect-video w-full h-full object-cover">
                            <img class="h-full" x-bind:src="_getImageData('stage_plots', data.image_id)?.url" />
                        </div>
                    </template>
                <?php } ?>
                <?php if (!$args['is_preview']) {
                    foreach ($args['stage_plot_ids'] as $image_id) {
                        $img_url = wp_get_attachment_image_url($image_id, 'medium');
                        if ($img_url) { ?>
                            <div class="flex justify-center aspect-video w-full h-full object-cover">
                                <img class="h-full" src="<?php echo esc_url($img_url); ?>" />
                            </div><?php
                        }
                    }
                } ?>
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

    <!-- Caption -->
    <div class="min-h-[18px]">
        <?php if ($args['is_preview']) { ?>
            <template x-for="(data, index) in orderedImageData['stage_plots']" :key="data.image_id">
                <template x-if="showStagePlotTab && currentIndex == index">
                    <div class="text-14" x-text="_getImageData('stage_plots', data.image_id)?.caption"></div>
                </template>
            </template>
        <?php } ?>
        <?php if (!$args['is_preview']) { ?>
            <?php foreach ($args['stage_plot_ids'] as $index => $image_id) {
                $caption = get_post_field('post_excerpt', $image_id); ?>
                <div class="text-14" x-show="showStagePlotTab && currentIndex == <?php echo $index; ?>" x-cloak><?php echo esc_html($caption); ?></div><?php
            } ?>
        <?php } ?>
    </div>

</span>
