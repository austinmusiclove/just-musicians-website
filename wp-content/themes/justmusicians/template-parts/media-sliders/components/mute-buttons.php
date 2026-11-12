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
