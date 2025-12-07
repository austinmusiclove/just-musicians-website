<div class="w-full bg-black/20 h-px"></div>
<div>
    <h4 class="text-16 mb-3 font-bold">Socials</h4>
    <div class="grid grid-cols-2 gap-x-6 gap-y-3 w-fit text-14">

        <!-- Instagram -->
        <?php if (!empty(get_field('instagram_handle'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('instagram_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis">@<?php echo get_field('instagram_handle'); ?></span>
        </a>
        <?php } ?>

        <!-- X -->
        <?php if (!empty(get_field('x_handle'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('x_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_X.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis">@<?php echo get_field('x_handle'); ?></span>
        </a>
        <?php } ?>

        <!-- Tiktok -->
        <?php if (!empty(get_field('tiktok_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('tiktok_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis">@<?php echo get_field('tiktok_handle'); ?></span>
        </a>
        <?php } ?>

        <!-- Facebook -->
        <?php if (!empty(get_field('facebook_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('facebook_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('name'); ?></span>
        </a>
        <?php } ?>

        <!-- Youtube -->
        <?php if (!empty(get_field('youtube_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('youtube_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo clean_url_for_display(get_field('youtube_url')); ?></span>
        </a>
        <?php } ?>

        <!-- Bandcamp -->
        <?php if (!empty(get_field('bandcamp_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('bandcamp_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Bandcamp.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo clean_url_for_display(get_field('bandcamp_url')); ?></span>
        </a>
        <?php } ?>

        <!-- Spotify -->
        <?php if (!empty(get_field('spotify_artist_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('spotify_artist_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('name'); ?></span>
        </a>
        <?php } ?>

        <!-- Apple -->
        <?php if (!empty(get_field('apple_music_artist_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('apple_music_artist_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_AppleMusic.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo get_field('name'); ?></span>
        </a>
        <?php } ?>

        <!-- Soundcloud -->
        <?php if (!empty(get_field('soundcloud_url'))) { ?>
        <a class="flex items-center gap-2" target="_blank"
            href="<?php echo get_field('soundcloud_url'); ?>"
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Soundcloud.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis"><?php echo clean_url_for_display(get_field('soundcloud_url')); ?></span>
        </a>
        <?php } ?>

    </div>
</div> <!-- End social media -->
