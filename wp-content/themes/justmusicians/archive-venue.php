<?php
/**
 * The template for displaying all venues
 *
 */

get_header();


?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10"> <!--Look here -->
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6">Browse Venues</h1>
        </div>

    </div>
</header>


<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0"
        x-data="{
            showScrollHint: false,
            _disableScrollZoom()        { disableScrollZoom(); },
            _handleMapWheelEvent(event) { handleMapWheelEvent(this, event); },
        }"
    >


        <div
            id="maplibre-overlay"
            class="hidden sm:block relative top-16 left-1/2 -translate-x-1/2 bg-grey text-white px-3.5 py-2 rounded text-[13px] z-[99] pointer-events-none max-w-[260px] text-center leading-tight"
            x-show="showScrollHint" x-cloak
            x-transition:enter="transition-opacity duration-200 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <span>Hold <strong>⌘</strong> and scroll to zoom</span>
        </div>
        <div id="map" class="h-[300px] sm:h-[500px]"
            x-on:wheel="_handleMapWheelEvent($event)"
            x-on:mouseleave="_disableScrollZoom()"
        >
        </div>


    </div>
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            )); ?>
        </div>
    </div>
</div>



<?php

get_footer();
