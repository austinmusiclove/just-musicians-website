<span
    x-data="{
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: <?php if (!$args['is_preview']) { echo count($args['stage_plot_ids']) > 0 ? count($args['stage_plot_ids']) : 1; } else { echo "orderedImageData['stage_plots'].length > 0 ? orderedImageData['stage_plots'].length : 1"; } ?>,
        _updateIndex(newIndex)  { this.previousIndex = this.currentIndex; this.currentIndex = newIndex; },
    }"
    <?php if ($args['is_preview']) { ?>x-init="$watch('orderedImageData', value => { totalSlides = orderedImageData['stage_plots'].length == 0 ? 1 : orderedImageData['stage_plots'].length; _updateIndex(0); })"<?php } ?>
    x-on:mouseleave="showArrows = false;"
    x-on:mouseenter="showArrows = true"
>
    <div class="bg-black aspect-video flex flex-col items-center justify-center relative overflow-hidden mb-[21px]" x-show="showStagePlotTab" x-cloak>
        <!-- Slider Track Container -->
        <div class="relative aspect-video w-full h-full overflow-hidden">
            <div class="flex h-full transition-transform duration-500 ease-in-out"
                x-bind:style="`transform: translateX(-${currentIndex * 100}%)`"
            >
                <?php if ($args['is_preview']) { ?>
                    <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                        <div class="w-full h-full flex-shrink-0 flex items-center justify-center bg-black">
                            <img class="h-full w-auto object-contain"
                                 x-bind:src="_getImageData('stage_plots', data.image_id)?.url"
                                 width="600" height="450"
                                 alt="Stage plot" />
                        </div>
                    </template>
                <?php } ?>

                <?php if (!$args['is_preview'] && !empty($args['stage_plot_ids'])) {
                    foreach ($args['stage_plot_ids'] as $image_id) {
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
                                     alt="Stage plot" />
                            </div><?php
                        }
                    }
                } ?>
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
