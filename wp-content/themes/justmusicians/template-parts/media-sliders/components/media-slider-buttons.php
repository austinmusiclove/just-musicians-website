<!-- Play -->
<div class="absolute transform left-2 bottom-2"
    @click="_updateIndex(1)"
    x-show="currentIndex == 0 && totalSlides > 1" x-cloak>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" width="32" height="32" alt="Play video" />
</div>
<!-- Pause -->
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
    x-show="currentIndex > 0 && _isPaused()" x-cloak>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/pause_circle.svg'; ?>" width="32" height="32" alt="Pause video" />
</div>
<!-- Mute -->
<div class="absolute transform left-2 bottom-2"
    @click="_toggleMuteAllVideos()"
    x-show="currentIndex > 0 && playersMuted" x-cloak>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/mute.svg'; ?>" width="24" height="24" alt="Mute audio" />
</div>
<!-- Unmute -->
<div class="absolute transform left-2 bottom-2"
    @click="_toggleMuteAllVideos()"
    x-show="currentIndex > 0 && !playersMuted" x-cloak>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/unmute.svg'; ?>" width="24" height="24" alt="Unmute audio" />
</div>
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
