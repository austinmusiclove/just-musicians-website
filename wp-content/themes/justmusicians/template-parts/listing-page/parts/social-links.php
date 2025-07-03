
<div class="w-full bg-black/20 h-px"></div>
<div>
    <h4 class="text-16 mb-3 font-bold">Socials</h4>
    <div class="grid grid-cols-2 gap-x-6 gap-y-3 w-fit text-14">

        <!-- Instagram -->
        <?php if (!empty(get_field('instagram_handle')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pInstagramHandle" x-cloak                <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pInstagramUrl"                      <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('instagram_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="'@' + pInstagramHandle" <?php } ?>>@<?php if (!$args['is_preview']) { echo get_field('instagram_handle'); } ?></span>
        </a>
        <?php } ?>

        <!-- X -->
        <?php if (!empty(get_field('x_handle')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pXHandle" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pXUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('x_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_X.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="'@' + pXHandle" <?php } ?>>@<?php if (!$args['is_preview']) { echo get_field('x_handle'); } ?></span>
        </a>
        <?php } ?>

        <!-- Tiktok -->
        <?php if (!empty(get_field('tiktok_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pTiktokUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pTiktokUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('tiktok_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="'@' + pTiktokHandle" <?php } ?>>@<?php if (!$args['is_preview']) { echo get_field('tiktok_handle'); } ?></span>
        </a>
        <?php } ?>

        <!-- Facebook -->
        <?php if (!empty(get_field('facebook_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pFacebookUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pFacebookUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('facebook_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pName" <?php } ?>><?php if (!$args['is_preview']) { echo get_field('name'); } ?></span>
        </a>
        <?php } ?>

        <!-- Youtube -->
        <?php if (!empty(get_field('youtube_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pYoutubeUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pYoutubeUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('youtube_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pYoutubeUrl" <?php } ?>><?php if (!$args['is_preview']) { echo clean_url_for_display(get_field('youtube_url')); } ?></span>
        </a>
        <?php } ?>

        <!-- Bandcamp -->
        <?php if (!empty(get_field('bandcamp_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pBandcampUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pBandcampUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('bandcamp_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Bandcamp.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pBandcampUrl" <?php } ?>><?php if (!$args['is_preview']) { echo clean_url_for_display(get_field('bandcamp_url')); } ?></span>
        </a>
        <?php } ?>

        <!-- Spotify -->
        <?php if (!empty(get_field('spotify_artist_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pSpotifyArtistUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pSpotifyArtistUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('spotify_artist_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pName" <?php } ?>><?php if (!$args['is_preview']) { echo get_field('name'); } ?></span>
        </a>
        <?php } ?>

        <!-- Apple -->
        <?php if (!empty(get_field('apple_music_artist_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pAppleMusicArtistUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pAppleMusicArtistUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('apple_music_artist_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_AppleMusic.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pName" <?php } ?>><?php if (!$args['is_preview']) { echo get_field('name'); } ?></span>
        </a>
        <?php } ?>

        <!-- Soundcloud -->
        <?php if (!empty(get_field('soundcloud_url')) or $args['is_preview']) { ?>
        <a class="flex items-center gap-2" target="_blank"
            <?php if ($args['is_preview'])  { ?> x-show="pSoundcloudUrl" x-cloak <?php } ?>
            <?php if ($args['is_preview'])  { ?> x-bind:href="pSoundcloudUrl" <?php } ?>
            <?php if (!$args['is_preview']) { ?> href="<?php echo get_field('soundcloud_url'); ?>" <?php } ?>
        >
            <img class="h-5 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Soundcloud.svg'; ?>" />
            <span class="whitespace-nowrap overflow-hidden text-ellipsis" <?php if ($args['is_preview']) { ?> x-text="pSoundcloudUrl" <?php } ?>><?php if (!$args['is_preview']) { echo clean_url_for_display(get_field('soundcloud_url')); } ?></span>
        </a>
        <?php } ?>

    </div>
</div> <!-- End social media -->
