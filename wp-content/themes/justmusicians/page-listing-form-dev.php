<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

$listing_data = null;
if (!empty($_GET['lid'])) {
    $listing_data = get_listing(['post_id' => $_GET['lid']]);
}
$clean_name        = $listing_data ? clean_str_for_doublequotes($listing_data["name"])        : null;
$clean_description = $listing_data ? clean_str_for_doublequotes($listing_data["description"]) : null;
$clean_city        = $listing_data ? clean_str_for_doublequotes($listing_data["city"])        : null;
$clean_state       = $listing_data ? clean_str_for_doublequotes($listing_data["state"])       : null;
$categories        = get_terms_decoded('mcategory', 'names');
$genres            = get_terms_decoded('genre', 'names');
$subgenres         = get_terms_decoded('subgenre', 'names');
$instrumentations  = get_terms_decoded('instrumentation', 'names');
$settings          = get_terms_decoded('setting', 'names');
$filename_prefix   = get_current_user_id() . '_' . time();
$ph_thumbnail      = get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp';


get_header();


?>

<!-- Error messages
<div class="top-28 md:top-16 bg-red-60 p-2 text-center sticky z-20 relative">
    Form submission unsuccessful. Please try again.
    <img class="close-button opacity-60 hover:opacity-100 absolute top-0.5 right-0.5 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" />
</div> -->

<div class="top-28 md:top-16 bg-yellow-60 p-2 text-center sticky z-20 relative">
Form submission successful! <a href="#" class="underline">View your listing.</a>
    <img class="close-button opacity-60 hover:opacity-100 absolute top-0.5 right-0.5 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" />
</div>

<div class="fixed top-0 left-1/2 w-1/2 bg-yellow-10 z-0 h-screen"></div>


<div class="container md:grid md:grid-cols-12 gap-20 min-h-[500px]"
    x-data="{
        pName:                  '<?php if ($listing_data) { echo $clean_name; } ?>',
        pDescription:           '<?php if ($listing_data) { echo $clean_description; } ?>',
        pCity:                  '<?php if ($listing_data) { echo $clean_city; } ?>',
        pState:                 '<?php if ($listing_data) { echo $clean_state; } ?>',
        pInstagramHandle:       '<?php if ($listing_data) { echo $listing_data["instagram_handle"]; } ?>',
        pInstagramUrl:          '<?php if ($listing_data) { echo $listing_data["instagram_url"]; } ?>',
        pTiktokHandle:          '<?php if ($listing_data) { echo $listing_data["tiktok_handle"]; } ?>',
        pTiktokUrl:             '<?php if ($listing_data) { echo $listing_data["tiktok_url"]; } ?>',
        pXHandle:               '<?php if ($listing_data) { echo $listing_data["x_handle"]; } ?>',
        pXUrl:                  '<?php if ($listing_data) { echo $listing_data["x_url"]; } ?>',
        pWebsite:               '<?php if ($listing_data) { echo $listing_data["website"]; } ?>',
        pFacebookUrl:           '<?php if ($listing_data) { echo $listing_data["facebook_url"]; } ?>',
        pYoutubeUrl:            '<?php if ($listing_data) { echo $listing_data["youtube_url"]; } ?>',
        pBandcampUrl:           '<?php if ($listing_data) { echo $listing_data["bandcamp_url"]; } ?>',
        pSpotifyArtistUrl:      '<?php if ($listing_data) { echo $listing_data["spotify_artist_url"]; } ?>',
        pAppleMusicArtistUrl:   '<?php if ($listing_data) { echo $listing_data["apple_music_artist_url"]; } ?>',
        pSoundcloudUrl:         '<?php if ($listing_data) { echo $listing_data["soundcloud_url"]; } ?>',
        pThumbnailSrc:          '<?php if (!empty($listing_data['thumbnail_url']))      { echo $listing_data['thumbnail_url'];                                  } else { echo $ph_thumbnail; } ?>',
        ensembleSizeCheckboxes:  <?php if (!empty($listing_data["ensemble_size"]))      { echo clean_arr_for_doublequotes($listing_data["ensemble_size"]);      } else { echo '[]'; } ?>,
        categoriesCheckboxes:    <?php if (!empty($listing_data["mcategory"]))          { echo clean_arr_for_doublequotes($listing_data["mcategory"]);          } else { echo '[]'; }?>,
        genresCheckboxes:        <?php if (!empty($listing_data["genre"]))              { echo clean_arr_for_doublequotes($listing_data["genre"]);              } else { echo '[]'; } ?>,
        subgenresCheckboxes:     <?php if (!empty($listing_data["subgenre"]))           { echo clean_arr_for_doublequotes($listing_data["subgenre"]);           } else { echo '[]'; } ?>,
        instCheckboxes:          <?php if (!empty($listing_data["instrumentation"]))    { echo clean_arr_for_doublequotes($listing_data["instrumentation"]);    } else { echo '[]'; } ?>,
        settingsCheckboxes:      <?php if (!empty($listing_data["setting"]))            { echo clean_arr_for_doublequotes($listing_data["setting"]);            } else { echo '[]'; } ?>,
        keywords:                <?php if (!empty($listing_data["keyword"]))            { echo clean_arr_for_doublequotes($listing_data["keyword"]);            } else { echo '[]'; } ?>,
        youtubeVideoUrls:        <?php if (!empty($listing_data["youtube_video_urls"])) { echo clean_arr_for_doublequotes($listing_data["youtube_video_urls"]); } else { echo '[]'; } ?>,
        pVideoIds:               <?php if (!empty($listing_data["youtube_video_ids"]))  { echo clean_arr_for_doublequotes($listing_data["youtube_video_ids"]);  } else { echo '[]'; } ?>,
        getListingLocation() { return this.pCity && this.pState ? `${this.pCity}, ${this.pState}` : this.pCity || this.pState || ''; },
        showGenre(term)      { return this.genresCheckboxes.includes(term); },
    }"
    x-on:updatethumbnail.window="pThumbnailSrc = $event.detail;"
>
    <div class="col-span-12 lg:col-span-6">

    <header class="pt-12 md:pt-20 pb-8 md:pb-16 flex justify-between items-center">
        <h1 class="font-bold text-32">Create a Listing</h1>
        <!------------ Submit Buttons ----------------->
        <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', []); ?>
    </header>

        <form id="listing-form" action="/wp-json/v1/listings" enctype="multipart/form-data" class="flex flex-col gap-4"
            hx-post="<?php echo site_url('wp-html/v1/listings'); ?>"
            hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
            hx-target="#result"
            hx-ext="disable-element" hx-disable-element="#htmx-submit-button"
            hx-indicator="#htmx-submit-button">
            <?php if ($listing_data) { ?><input type="hidden" id="post_id" name="post_id" value="<?php echo $_GET['lid']; ?>"><?php } ?>
            <!--
            <input type="hidden" id="verified-venues" name="verified-venues">
            -->

            <!------------ Page Load Toasts ----------------->
            <div>
                <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'create') {
                    echo get_template_part('template-parts/global/toasts/success-toast', '', [ 'message' => 'Listing Created Successfully' ]);
                } ?>
            </div>


            <div class="flex flex-col gap-8">

                <!------------ Basic Information ----------------->
                <?php echo get_template_part('template-parts/listing-form/basic-info', '', []); ?>
                
                <!------------ Contact and Links ----------------->
                <?php echo get_template_part('template-parts/listing-form/contact', '', []); ?>

                <!------------ Search Optimization / Taxonomies ----------------->
                <?php echo get_template_part('template-parts/listing-form/search-optimization', '', []); ?>
                
                <!------------ Media ----------------->
                <?php echo get_template_part('template-parts/listing-form/media', '', []); ?>
                
                <!------------ Calendar ----------------->
                <?php echo get_template_part('template-parts/listing-form/calendar', '', []); ?>

               <!------------ Submit Buttons ----------------->
               <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', []); ?>


            <div id="result"></div>

            <!------------ Form Submit Toasts ----------------->
            <div class="h-20">
                <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'post-error-toast']); ?>
                <?php echo get_template_part('template-parts/global/toasts/success-toast', '', ['event_name' => 'post-success-toast']); ?>
            </div>
        </form>
    </div>
</div>


    <div class="bg-yellow-10 hidden lg:block md:col-span-6">
        <div class="sticky top-32"
            x-data='{
                players: {},
                playersMuted: true,
                playersPaused: false,
                _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
                _pauseAllPlayers()             { pauseAllPlayers(this); },
                _pausePlayer(playerId)         { pausePlayer(this, playerId); },
                _playPlayer(playerId)          { playPlayer(this, playerId); },
                _toggleMute()                  { toggleMute(this); },
                _setupVisibilityListener()     { setupVisibilityListener(this); },
            }'
            x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoId);"
            x-on:pause-all-youtube-players="_pauseAllPlayers()"
            x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
            x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
            x-on:mute-youtube-players="_toggleMute()"
            x-init="_setupVisibilityListener()">

            <div class="flex items-start justify-between mb-2.5 border-b border-black/20 pb-2">
                <h2 class="text-25 font-bold">Preview</h2>
                <div class="flex gap-2 items-start">
                    <?php $button_class = 'text-18 tab-heading pb-1 cursor-pointer'; ?>
                    <div data-tab="search-results" class="<?php echo $button_class; ?> active">Search Results</div>
                    <div data-tab="listing-page" class="<?php echo $button_class; ?>">Listing Page</div>
                </div>
            </div>

            <?php echo get_template_part('template-parts/search/standard-listing', '', [
                'name'                          => $listing_data ? $clean_name : 'Name',
                'location'                      => $listing_data ? $clean_city . ', ' . $clean_state : 'City, State',
                'description'                   => $listing_data ? $clean_description : 'Description',
                'genres'                        => $genres, // pass all genres; alpine_show_genre func will show the selected options
                'thumbnail_url'                 => $listing_data ? $listing_data['thumbnail_url']          : '',
                'verified'                      => $listing_data ? $listing_data['verified']               : false,
                'website'                       => $listing_data ? $listing_data['website']                : '',
                'facebook_url'                  => $listing_data ? $listing_data['facebook_url']           : '',
                'instagram_url'                 => $listing_data ? $listing_data['instagram_url']          : '',
                'x_url'                         => $listing_data ? $listing_data['x_url']                  : '',
                'youtube_url'                   => $listing_data ? $listing_data['youtube_url']            : '',
                'tiktok_url'                    => $listing_data ? $listing_data['tiktok_url']             : '',
                'bandcamp_url'                  => $listing_data ? $listing_data['bandcamp_url']           : '',
                'spotify_artist_url'            => $listing_data ? $listing_data['spotify_artist_url']     : '',
                'apple_music_artist_url'        => $listing_data ? $listing_data['apple_music_artist_url'] : '',
                'soundcloud_url'                => $listing_data ? $listing_data['soundcloud_url']         : '',
                'youtube_video_ids'             => $listing_data ? $listing_data['youtube_video_ids']      : [],
                'youtube_player_ids'            => $listing_data ? $listing_data['youtube_video_ids']      : [],
                'lazyload_thumbnail'            => false,
                'last'                          => false,
                'is_preview'                    => true,
                'alpine_name'                   => 'pName',
                'alpine_location'               => 'getListingLocation()',
                'alpine_description'            => 'pDescription',
                'alpine_instagram_url'          => 'pInstagramUrl',
                'alpine_tiktok_url'             => 'pTiktokUrl',
                'alpine_x_url'                  => 'pXUrl',
                'alpine_website'                => 'pWebsite',
                'alpine_facebook_url'           => 'pFacebookUrl',
                'alpine_youtube_url'            => 'pYoutubeUrl',
                'alpine_bandcamp_url'           => 'pBandcampUrl',
                'alpine_spotify_artist_url'     => 'pSpotifyArtistUrl',
                'alpine_apple_music_artist_url' => 'pAppleMusicArtistUrl',
                'alpine_soundcloud_url'         => 'pSoundcloudUrl',
                'alpine_show_genre'             => 'showGenre',
                'alpine_thumbnail_src'          => 'pThumbnailSrc',
                'alpine_video_ids'              => 'pVideoIds',
            ]); ?>
        </div>
    </div>
</div>

<?php
get_footer();

