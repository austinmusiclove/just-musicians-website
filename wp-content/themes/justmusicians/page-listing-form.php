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
$is_update         = !is_null($listing_data);
$is_published      = (!is_null($listing_data) and $listing_data['post_status'] == 'publish');
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

<div class="hidden lg:block fixed top-0 left-1/2 w-1/2 bg-yellow-10 z-0 h-screen"></div>

<div id="sticky-sidebar" class="hidden xl:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar collapsed">
        <?php echo get_template_part('template-parts/account/sidebar', '', [
            'collapsible' => true
        ]); ?>
    </div>
</div>


<div class="lg:container min-h-[500px]"
    x-data="{
        postStatus:             '',
        pName:                  '<?php if ($listing_data) { echo $clean_name; } ?>',
        pDescription:           '<?php if ($listing_data) { echo $clean_description; } ?>',
        pCity:                  '<?php if ($listing_data) { echo $clean_city; } ?>',
        pState:                 '<?php if ($listing_data) { echo $clean_state; } ?>',
        pZipCode:               '<?php if ($listing_data) { echo $listing_data["zip_code"]; } ?>',
        pBio:                   '<?php if ($listing_data) { echo clean_str_for_doublequotes($listing_data["bio"]); } ?>',
        pEmail:                 '<?php if ($listing_data) { echo $listing_data["email"]; } ?>',
        pPhone:                 '<?php if ($listing_data) { echo $listing_data["phone"]; } ?>',
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
        showCategory(term)   { return this.categoriesCheckboxes.includes(term); },
        showGenre(term)      { return this.genresCheckboxes.includes(term); },
    }"
    x-on:updatethumbnail.window="pThumbnailSrc = $event.detail;"
>
    <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">
        <div class="col-span-12 lg:col-span-6 relative z-0">

            <div class="mx-auto" style="max-width: 48rem">

                <form id="listing-form" action="/wp-json/v1/listings" enctype="multipart/form-data" class="flex flex-col gap-4"
                    x-ref="listingForm"
                    hx-post="<?php echo site_url('wp-html/v1/listings'); ?>"
                    hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
                    hx-target="#result"
                    hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
                    hx-indicator=".htmx-submit-button"
                >

                    <header class="pt-4 sm:pt-20 xl:pt-32 mb-4 sm:mb-12 gap-12 sm:gap-4 flex flex-col-reverse sm:flex-row justify-between sm:items-center">
                        <?php if (!$is_update) { ?><h1 class="font-bold text-28 sm:text-32 md:text-28 xl:text-32 w-full">Create a Listing</h1><?php } ?>
                        <?php if ($is_update)  { ?><h1 class="font-bold text-28 sm:text-32 md:text-28 xl:text-32 w-full">Update Listing</h1><?php } ?>
                        <!------------ Submit Buttons ----------------->
                        <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', [
                            'is_published' => $is_published,
                            'permalink'    => $listing_data ? $listing_data['permalink'] : '#',
                            'instance'     => 'Top',
                        ]); ?>
                    </header>

                    <input type="hidden" name="post_status" x-model="postStatus" />
                    <?php if ($listing_data) { ?><input type="hidden" id="post_id" name="post_id" value="<?php echo $_GET['lid']; ?>"><?php } ?>
                    <!--
                    <input type="hidden" id="verified-venues" name="verified-venues">
                    -->

                    <!------------ Page Load Toasts ----------------->
                    <div>
                        <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'create')        { ?><span x-init="$dispatch('success-toast', {'message': 'Listing Created Successfully'});"></span><?php } ?>
                        <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'create-draft')  { ?><span x-init="$dispatch('success-toast', {'message': 'Listing Draft Created Successfully'});"></span><?php } ?>
                        <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'publish-draft') { ?><span x-init="$dispatch('success-toast', {'message': 'Listing Draft Published Successfully'});"></span><?php } ?>
                    </div>


                    <div class="flex flex-col gap-10">

                        <!------------ Basic Information ----------------->
                        <?php echo get_template_part('template-parts/listing-form/basic-info', '', []); ?>

                        <!------------ Contact and Links ----------------->
                        <?php echo get_template_part('template-parts/listing-form/contact', '', []); ?>

                        <!------------ Search Optimization / Taxonomies ----------------->
                        <?php echo get_template_part('template-parts/listing-form/search-optimization', '', [
                            'categories'       => $categories,
                            'genres'           => $genres,
                            'subgenres'        => $subgenres,
                            'instrumentations' => $instrumentations,
                            'settings'         => $settings,
                        ]); ?>

                        <!------------ Media ----------------->
                        <?php //echo get_template_part('template-parts/listing-form/media', '', []); ?>

                        <!------------ Calendar ----------------->
                        <?php //echo get_template_part('template-parts/listing-form/calendar', '', []); ?>

                        <!------------ Venues Played ----------------->
                        <?php //echo get_template_part('template-parts/listing-form/venues', '', []); ?>

                        <!------------ Submit Buttons ----------------->
                        <div class="mt-8">
                            <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', [
                                'is_published' => $is_published,
                                'permalink'    => $listing_data ? $listing_data['permalink'] : '#',
                                'instance'     => 'Bottom',
                            ]); ?>
                        </div>

                        <div id="result"></div>

                    </div>
                </form>
            </div>
        </div>



        <div class="bg-yellow-10 hidden lg:block md:col-span-6 pb-4">
            <div class="sticky top-36" x-data="{
                showSearchResultTab: true,
                showPageTab: false,
                hideTabs() {
                    this.showSearchResultTab = false;
                    this.showPageTab = false;
                },
            }">

                <div class="flex items-start justify-between mb-6 border-b border-black/20">
                    <h2 class="text-25 font-bold">Preview</h2>
                    <div class="flex gap-6 items-start">
                        <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showSearchResultTab}" x-on:click="hideTabs(); showSearchResultTab = true;">Search Results</div>
                        <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showPageTab}" x-on:click="hideTabs(); showPageTab = true;">Listing Page</div>
                    </div>
                </div>


                <div x-show="showPageTab" x-cloak >
                    <!-- Hero -->
                    <?php echo get_template_part('template-parts/listing/hero', '', array(
                        'instance'          => 'listing-form',
                        'genres'            => $genres,
                    )); ?>

                    <!-- Content -->
                    <?php echo get_template_part('template-parts/listing/content', '', array(
                        'instance'          => 'listing-form',
                        'categories'        => $categories,
                        'genres'            => $genres,
                        'subgenres'         => $subgenres,
                        'instrumentations'  => $instrumentations,
                        'settings'          => $settings,
                        'youtube_video_ids' => [],
                    )); ?>
                </div>

                <div x-show="showSearchResultTab" x-cloak
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

                    <?php echo get_template_part('template-parts/search/standard-listing', '', [
                        'name'                          => $listing_data ? $clean_name : 'Performer or Band Name',
                        'location'                      => $listing_data ? $clean_city . ', ' . $clean_state : 'City, State',
                        'description'                   => $listing_data ? $clean_description : 'Description',
                        'genres'                        => $genres, // pass all genres; x-show expression will show the selected options
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
                        'instance'                      => 'listing-form',
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
                        'alpine_thumbnail_src'          => 'pThumbnailSrc',
                        'alpine_video_ids'              => 'pVideoIds',
                    ]); ?>

                    <div class="opacity-50">
                        <?php
                            echo get_template_part('template-parts/search/standard-listing-skeleton', '', []);
                            echo get_template_part('template-parts/search/standard-listing-skeleton', '', []);
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cover image popup -->
<div data-parent-tab="cover" data-type="popup" data-screen="2" class="hidden popup-wrapper px-2 pt-28 md:pt-0 w-screen h-screen  fixed top-0 left-0 z-50 flex items-center justify-center">
    <div data-show="cover-1" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;">

        <div class="px-6 pt-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-25 w-full">Crop your cover image</h4>
                <img data-show="cover-1" class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"/>
            </div>

            <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/cropper.jpg' ;?>">
        </div>

        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Image must be cropped into 4:3 aspect ratio.</span>
            <button data-show="cover-3" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Apply</button>
        </div>

    </div>
</div>

<!-- Thumbnail popup -->
<div data-parent-tab="thumbnails" data-type="popup" data-screen="2" class="hidden popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center">
    <div data-show="thumbnails-1" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;">

        <div class="px-6 pt-4">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-25 w-full">Crop your thumbnail</h4>
                <img data-show="thumbnails-1" class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"/>
            </div>
            <div class="grid sm:grid-cols-2">
                <img class="w-full h-full object-cover mx-auto" style="max-width: 28rem" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/cropper.jpg' ;?>">
                <div class="sm:px-4 md:px-10 py-4">
                    <h5 class="font-bold text-18 mb-3">Link taxonomy terms</h5>
                    <p class="mb-4 text-16">Thumbnails you upload will be listed in order of their relevance to the current search term</p>

                    <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                        <!-- Tag 1 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">gospel choir</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 2 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center bg-yellow-40 font-bold px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">live looper</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 3 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center bg-yellow-40 font-bold px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">orchestra</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 4 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">psychedelic steam punk</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 5 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">world music</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                    </div>
                    <p class="text-14 text-grey">2/8 terms selected</p>
                </div>
            </div>
        </div>


        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Add more taxonomy terms to your profile to see more options.</span>
            <button data-show="thumbnails-3" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Apply</button>
        </div>

    </div>
</div>

<!-- Youtube link popup -->
<div data-parent-tab="youtube-videos" data-type="popup" data-screen="2" class="hidden px-4 pt-12 popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center">
    <div data-show="youtube-videos-1" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;">

        <div class="px-6 pt-4">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-25 w-full">Add a YouTube video</h4>
                <img data-show="youtube-videos-1" class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"/>
            </div>
            <div>
                <div class="mb-6">
                    <form class="sm:grid grid-cols-4 gap-2">
                        <div class="col-span-3">
                            <label class="mb-1 inline-block">Video url<span class="text-red">*</span></label>
                            <input class="mb-2" type="text" placeholder="https://" required/>
                        </div>
                        <div class="col-span-1">
                            <label class="mb-1 inline-block">Start time</label>
                            <input type="text" placeholder="e.g. 40"/>
                        </div>
                    </form>
                </div>
                <div class="border-t border-black/20 -mx-6 pt-6 sm:pt-4 pb-2 sm:pb-0 px-6">
                    <h5 class="font-bold text-18 mb-4">Link taxonomy terms</h5>

                    <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                        <!-- Tag 1 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">gospel choir</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 2 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center bg-yellow-40 font-bold px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">live looper</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 3 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center bg-yellow-40 font-bold px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">orchestra</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 4 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">psychedelic steam punk</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                        <!-- Tag 5 -->
                        <!--
                        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                            <div class="flex items-center border border-black/20 px-3 h-7 rounded-full">
                                <span class="text-14 w-fit">world music</span>
                                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                            </div>
                        </div>
                        -->
                    </div>
                    <p class="text-14 text-grey">2/8 terms selected</p>
                </div>
            </div>
        </div>


        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Add more taxonomy terms to your profile to see more options.</span>
            <button data-show="youtube-videos-3" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Apply</button>
        </div>

    </div>
</div>


<!-- Stage Plot popup -->
<div data-parent-tab="stage-plot-images" data-type="popup" data-screen="2" class="hidden px-4 popup-wrapper pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center">
    <div data-show="stage-plot-images-1" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;">

        <div class="px-6 pt-4">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-25 w-full">Add a stage plot image</h4>
                <img data-show="stage-plot-images-1" class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"/>
            </div>
            <div>
                <div class="mb-6 grid sm:grid-cols-2 gap-2">
                    <div class="aspect-4/3 w-48 border border-black/20 mx-auto">
                        <img class="h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/stage-plot.jpg' ;?>">
                    </div>
                    <div class="max-w-1/2 break-all text-center flex items-center flex-col justify-center gap-4 px-4 text-center text-grey">
                        stage-plot-1.jpg
                    </div>
                </div>
                <div class="border-t border-black/20 -mx-6 pt-4 px-6">
                    <form>
                        <label class="mb-1 inline-block">Caption</label>
                        <input type="text"/>
                    </form>
                </div>

            </div>
        </div>


        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Let potential clients know what this is.</span>
            <button data-show="stage-plot-images-3" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Apply</button>
        </div>

    </div>
</div>


<?php
get_footer();
