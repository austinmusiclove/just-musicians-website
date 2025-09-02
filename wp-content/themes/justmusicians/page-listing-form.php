<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

$post_id = null;
$listing_data = null;
if (!is_user_logged_in()) { wp_redirect(site_url()); }                          // Don't allow non logged in users to use the form
if (!empty($_GET['lid'])) {
    $user_listings    = get_user_meta(get_current_user_id(), 'listings', true); // Get user's listings
    if (!in_array($_GET['lid'], $user_listings)) { wp_redirect(site_url()); }   // Don't allow opening form for a listing that does not belong to the logged in user
    $listing_data     = get_listing(['post_id' => $_GET['lid']]);               // Get listing data
}
$is_update            = !is_null($listing_data);
$is_published         = (!is_null($listing_data) and $listing_data['post_status'] == 'publish');
$verified_venue_ids   = ($listing_data and is_array($listing_data['venues_played_verified']))   ? $listing_data['venues_played_verified']   : [];
$unverified_venue_ids = ($listing_data and is_array($listing_data['venues_played_unverified'])) ? $listing_data['venues_played_unverified'] : [];
$categories           = get_terms_decoded('mcategory', 'names');
$genres               = get_terms_decoded('genre', 'names');
$subgenres            = get_terms_decoded('subgenre', 'names');
$instrumentations     = get_terms_decoded('instrumentation', 'names');
$settings             = get_terms_decoded('setting', 'names');
$spotify_artist_id    = $listing_data['spotify_artist_id'];
$ph_thumbnail         = get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp';

// Get venues post data
$all_venues_played = [];
if ($listing_data) {
    foreach (array_unique(array_merge($verified_venue_ids, $unverified_venue_ids)) as $venue_id) {
        $all_venues_played[] = [
            'ID'               => $venue_id,
            'name'             => get_field('name',             $venue_id),
            'street_address'   => get_field('street_address',   $venue_id),
            'address_locality' => get_field('address_locality', $venue_id),
            'postal_code'      => get_field('postal_code',      $venue_id),
            'address_region'   => get_field('address_region',   $venue_id),
    ];}
}

get_header();
?>

<div class="hidden lg:block fixed top-0 left-1/2 w-1/2 bg-yellow-10 z-0 h-screen"></div>

<div id="sticky-sidebar" class="hidden xl:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar">
        <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => true ]); ?>
    </div>
</div>


<div class="lg:container min-h-[500px]"
    x-data="{
        showImageEditPopup:     false,
        showStagePlotPopup:     false,
        showYoutubeLinkPopup:   false,
        postStatus:             '<?php if ($listing_data) { echo $listing_data['post_status']; } else { echo 'draft'; } ?>',
        pName:                  '<?php if ($listing_data) { echo clean_str_for_doublequotes($listing_data["name"]); } ?>',
        pDescription:           '<?php if ($listing_data) { echo clean_str_for_doublequotes($listing_data["description"]); } ?>',
        pCity:                  '<?php if ($listing_data) { echo clean_str_for_doublequotes($listing_data["city"]); } ?>',
        pState:                 '<?php if ($listing_data) { echo clean_str_for_doublequotes($listing_data["state"]); } ?>',
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
        pSpotifyArtistId:       '<?php if ($listing_data) { echo $listing_data["spotify_artist_id"]; } ?>',
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
        allVenuesPlayed:         <?php if (!empty($all_venues_played))                  { echo clean_arr_for_doublequotes($all_venues_played);                  } else { echo '[]'; } ?>,
        verifiedVenueIds:        <?php if (!empty($verified_venue_ids))                 { echo clean_arr_for_doublequotes($verified_venue_ids);                 } else { echo '[]'; } ?>,
        unverifiedVenueIds:      <?php if (!empty($unverified_venue_ids))               { echo clean_arr_for_doublequotes($unverified_venue_ids);               } else { echo '[]'; } ?>,
        youtubeVideoData:        <?php if (!empty($listing_data["youtube_video_data"])) { echo clean_arr_for_doublequotes($listing_data["youtube_video_data"]); } else { echo '[]'; } ?>,
        orderedImageData: {
            'cover_image': [
                {
                    'image_id':      'cover_image',
                    'attachment_id': '<?php if (!empty($listing_data['thumbnail_id']))        { echo $listing_data['thumbnail_id'];                                                } else { echo ''; } ?>',
                    'url':           '<?php if (!empty($listing_data['thumbnail_url']))       { echo $listing_data['thumbnail_url'];                                               } else { echo ''; } ?>',
                    'filename':      '<?php if (!empty($listing_data['thumbnail_filename']))  { echo $listing_data['thumbnail_filename'];                                          } else { echo ''; } ?>',
                    'mediatags':      <?php if (!empty($listing_data["thumbnail_terms"]))     { echo clean_arr_for_doublequotes($listing_data["thumbnail_terms"]);                 } else { echo '[]'; } ?>,
                    'loading':       false,
                    'worker':        null,
                },
            ],
            'listing_images':         <?php if (!empty($listing_data["listing_images_data"])) { echo clean_arr_for_doublequotes($listing_data["listing_images_data"]);               } else { echo '[]'; } ?>,
            'stage_plots':            <?php if (!empty($listing_data["stage_plots_data"]))    { echo clean_arr_for_doublequotes($listing_data["stage_plots_data"]);                  } else { echo '[]'; } ?>,
        },
        getListingLocation() { return this.pCity && this.pState ? `${this.pCity}, ${this.pState}` : this.pCity || this.pState ? this.pCity || this.pState || '' : 'City, State'; },

        cropper:                    null,
        showCropperDisplay:         true,
        popupImageSpinner: false,
        _initCropper(displayElement, imageType, imageId)                { initCropper(this, displayElement, imageType, imageId, this._getImageData(imageType, imageId).url, false); },
        _initCropperFromFile(event, displayElement, imageType, imageId) { initCropperFromFile(this, event, displayElement, imageType, imageId); },

        currentImageId: 'cover_image',
        currentYtIndex:  -1,
        _getImageData(imageType, imageId)                             { return getImageData(this, imageType, imageId); },
        _toggleImageTerm(imageType, imageId, term)                    { toggleImageTerm(this, imageType, imageId, term); },
        _toggleYoutubeLinkTerm(index, term)                           { toggleYoutubeLinkTerm(this, index, term); },
        _removeImage(imageType, imageId)                              { removeImage(this, imageType, imageId); },
        _reorderImage(imageType, imageId, newPosition)                { reorderImage(this, imageType, imageId, newPosition); },
        _updateFileInputs(imageType)                                  { updateFileInputs(this, imageType); },
        _updateAttachmentIds(attachmentIds)                           { updateAttachmentIds(this, attachmentIds); },
        _getAllMediatags()                                            { return getAllMediatags(this); },

        _addYoutubeUrl(input)    { addYoutubeUrl(this, input); },
        _removeYoutubeUrl(index) { removeYoutubeUrl(this, index); },
    }"
    x-on:updateimageids.window="_updateAttachmentIds($event.detail)"
>
    <form id="listing-form" enctype="multipart/form-data" class="flex flex-col gap-4"
        x-ref="listingForm"
        hx-post="<?php echo site_url('wp-html/v1/listings'); ?>"
        hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
        hx-target="#result"
        hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
        hx-indicator=".htmx-submit-button"
    >
        <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">
            <div class="col-span-12 lg:col-span-6 relative z-0">

                <div class="mx-auto" style="max-width: 48rem">


                    <!-- Title and submit buttons -->
                    <header class="pt-4 sm:pt-20 xl:pt-32 mb-4 sm:mb-12 gap-12 sm:gap-4 flex flex-col-reverse sm:flex-row justify-between sm:items-center">
                        <?php if (!$is_update) { ?><h1 class="font-bold text-28 sm:text-32 md:text-28 xl:text-32 w-full">Create a Listing</h1><?php } ?>
                        <?php if ($is_update)  { ?><h1 class="font-bold text-28 sm:text-32 md:text-28 xl:text-32 w-full">Update Listing</h1><?php } ?>
                        <!------------ Submit Buttons ----------------->
                        <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', [
                            'is_published' => $is_published,
                            'permalink'    => $listing_data ? $listing_data['permalink'] : '#',
                        ]); ?>
                    </header>

                    <!-- Hidden inputs -->
                    <?php if ($listing_data) { ?><input type="hidden" id="post_id" name="post_id" value="<?php echo $_GET['lid']; ?>"><?php } ?>
                    <input type="hidden" name="post_status" x-model="postStatus" />
                    <input type="hidden" name="mediatags" x-bind:value="JSON.stringify(_getAllMediatags())">


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
                        <?php echo get_template_part('template-parts/listing-form/media', '', []); ?>

                        <!------------ Calendar ----------------->
                        <?php //echo get_template_part('template-parts/listing-form/calendar', '', []); ?>

                        <!------------ Venues Played ----------------->
                        <?php echo get_template_part('template-parts/listing-form/venues', '', []); ?>

                        <!------------ Submit Buttons ----------------->
                        <div class="mt-8">
                            <?php echo get_template_part('template-parts/listing-form/submit-buttons', '', [
                                'is_published' => $is_published,
                                'permalink'    => $listing_data ? $listing_data['permalink'] : '#',
                            ]); ?>
                        </div>

                        <div id="result"></div>

                    </div>
                </div>
            </div>


            <!-- Preview -->
            <div class="bg-yellow-10 hidden lg:block md:col-span-6 pb-4">
                <div class="sticky top-36" x-data="{
                    showSearchResultTab: false,
                    showPageTab: true,
                    hideTabs() {
                        this.showSearchResultTab = false;
                        this.showPageTab = false;
                    },
                }">

                    <!-- Preview tabs -->
                    <div class="flex items-start justify-between mb-6 border-b border-black/20">
                        <h2 class="text-25 font-bold">Preview</h2>
                        <div class="flex gap-6 items-start">
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showPageTab}" x-on:click="hideTabs(); showPageTab = true;">Listing Page</div>
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showSearchResultTab}" x-on:click="hideTabs(); showSearchResultTab = true;">Search Results</div>
                        </div>
                    </div>


                    <!-- Full page preview -->
                    <div class="overflow-scroll max-h-[76vh]" x-show="showPageTab" x-cloak >
                        <?php echo get_template_part('template-parts/listing-page/hero', '', array(
                            'instance'          => 'listing-form',
                            'genres'            => $genres,
                        )); ?>
                        <?php echo get_template_part('template-parts/listing-page/content', '', array(
                            'instance'           => 'listing-form',
                            'categories'         => $categories,
                            'genres'             => $genres,
                            'subgenres'          => $subgenres,
                            'instrumentations'   => $instrumentations,
                            'settings'           => $settings,
                            'youtube_video_data' => null,
                            'venues_combined'    => null,
                            'spotify_artist_id'  => $spotify_artist_id,
                        )); ?>
                    </div>

                    <!-- Search result preview -->
                    <div x-show="showSearchResultTab" x-cloak
                        x-data='{
                            players: {},
                            playersMuted: true,
                            playersPaused: false,
                            _initPlayer(playerId, videoData) { initPlayer(this, playerId, videoData); },
                            _pauseAllPlayers()               { pauseAllPlayers(this); },
                            _pausePlayer(playerId)           { pausePlayer(this, playerId); },
                            _playPlayer(playerId)            { playPlayer(this, playerId); },
                            _toggleMute()                    { toggleMute(this); },
                            _setupVisibilityListener()       { setupVisibilityListener(this); },
                        }'
                        x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
                        x-on:pause-all-youtube-players="_pauseAllPlayers()"
                        x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                        x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                        x-on:mute-youtube-players="_toggleMute()"
                        x-init="_setupVisibilityListener()">

                        <?php echo get_template_part('template-parts/listings/standard-listing', '', [
                            'genres'                        => $genres, // pass all genres; x-show expression will show the selected options
                            'verified'                      => $listing_data ? $listing_data['verified']               : false,
                            'lazyload_thumbnail'            => false,
                            'last'                          => false,
                            'instance'                      => 'listing-form',
                        ]); ?>

                        <div class="opacity-50">
                            <?php
                                echo get_template_part('template-parts/listings/standard-listing-skeleton', '', []);
                                echo get_template_part('template-parts/listings/standard-listing-skeleton', '', []);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Image edit popup -->
        <?php echo get_template_part('template-parts/listing-form/popups/image-edit-popup', '', [
            'categories'       => $categories,
            'instrumentations' => $instrumentations,
            'settings'         => $settings,
        ]); ?>

        <!-- Stage Plot popup -->
        <?php echo get_template_part('template-parts/listing-form/popups/stage-plot-popup', '', []); ?>

        <!-- Youtube link popup -->
        <?php echo get_template_part('template-parts/listing-form/popups/youtube-link-popup', '', [
            'categories'       => $categories,
            'instrumentations' => $instrumentations,
            'settings'         => $settings,
            'genres'           => $genres,
            'subgenres'        => $subgenres,
        ]); ?>

    </form>

</div>


<?php
get_footer();
