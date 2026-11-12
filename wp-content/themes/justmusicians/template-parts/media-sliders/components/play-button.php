<!-- Play -->
<div class="absolute transform left-2 bottom-2"
    @click="_updateIndex(1)"
    x-show="currentIndex == 0 && totalSlides > 1" x-cloak>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/slider/play_circle.svg'; ?>" width="32" height="32" alt="Play video" />
</div>
