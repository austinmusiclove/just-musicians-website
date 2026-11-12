<!-- Left Arrow -->
<div class="absolute top-1/2 transform -translate-y-1/2 left-4 transition-all duration-100 ease-in-out"
    @click="_updateIndex((currentIndex === 0) ? totalSlides - 1 : currentIndex - 1)"
    x-show="currentIndex > 0 && showArrows" x-cloak
    x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0" >
    <img class="rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" width="24" height="24" alt="Previous slide" />
</div>
<!-- Right Arrow -->
<div class="absolute top-1/2 transform -translate-y-1/2 right-4 transition-all duration-100 ease-in-out"
    @click="_updateIndex((currentIndex === totalSlides - 1) ? 0 : currentIndex + 1)"
    x-show="currentIndex < totalSlides - 1 && showArrows" x-cloak
    x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0" >
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/arrow.svg'; ?>" width="24" height="24" alt="Next slide" />
</div>
