<!-- Links -->
<div class="flex items-center gap-1">
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['website']; ?>"                                             <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['website']) ? 'true' : 'false'; ?>"                <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pWebsite"                                                             <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pWebsite" x-cloak                                                          <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Website.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['instagram_url']; ?>"                                       <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['instagram_url']) ? 'true' : 'false'; ?>"          <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pInstagramUrl"                                                        <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pInstagramUrl" x-cloak                                                     <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Instagram.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['x_url']; ?>"                                               <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['x_url']) ? 'true' : 'false'; ?>"                  <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pXUrl"                                                                <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pXUrl" x-cloak                                                             <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_X.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['tiktok_url']; ?>"                                          <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['tiktok_url']) ? 'true' : 'false'; ?>"             <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pTiktokUrl"                                                           <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pTiktokUrl" x-cloak                                                        <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_TikTok.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['facebook_url']; ?>"                                        <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['facebook_url']) ? 'true' : 'false'; ?>"           <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pFacebookUrl"                                                         <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pFacebookUrl" x-cloak                                                      <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Facebook.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['youtube_url']; ?>"                                         <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['youtube_url']) ? 'true' : 'false'; ?>"            <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pYoutubeUrl"                                                          <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pYoutubeUrl" x-cloak                                                       <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_YouTube.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['bandcamp_url']; ?>"                                        <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['bandcamp_url']) ? 'true' : 'false'; ?>"           <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pBandcampUrl"                                                         <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pBandcampUrl" x-cloak                                                      <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Bandcamp.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['spotify_artist_url']; ?>"                                  <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['spotify_artist_url']) ? 'true' : 'false'; ?>"     <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pSpotifyArtistUrl"                                                    <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pSpotifyArtistUrl" x-cloak                                                 <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Spotify.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['apple_music_artist_url']; ?>"                              <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['apple_music_artist_url']) ? 'true' : 'false'; ?>" <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pAppleMusicArtistUrl"                                                 <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pAppleMusicArtistUrl" x-cloak                                              <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_AppleMusic.svg'; ?>" />
    </a>
    <a target="_blank"
        <?php if (!$args['is_preview']) { ?> href="<?php echo $args['soundcloud_url']; ?>"                                      <?php } ?>
        <?php if (!$args['is_preview']) { ?> x-show="<?php echo !empty($args['soundcloud_url']) ? 'true' : 'false'; ?>"         <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-bind:href="pSoundcloudUrl"                                                       <?php } ?>
        <?php if ($args['is_preview'])  { ?> x-show="pSoundcloudUrl" x-cloak                                                    <?php } ?>>
        <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/_Soundcloud.svg'; ?>" />
    </a>
</div>
