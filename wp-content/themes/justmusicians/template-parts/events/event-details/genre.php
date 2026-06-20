<div class="flex items-start gap-1" x-show="genres.length > 0" x-cloak>

    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/music.svg" />

    <div class="flex flex-wrap items-center gap-1">
        <template x-for="genre in genres">
            <span class="bg-yellow-light px-2 py-0.5 rounded-full font-bold text-12" x-text="genre"></span>
        </template>
    </div>

</div>

<div class="flex items-center gap-1" x-show="genres.length == 0" x-cloak>
    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/music.svg" />
    <p class="text-16 text-black/50">No genres specified</p>
</div>
