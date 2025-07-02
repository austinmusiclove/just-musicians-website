<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

$listing_data = null;
if (!empty($_GET['lid'])) {
    $listing_data     = get_listing(['post_id' => $_GET['lid']]);
}
$is_update            = !is_null($listing_data);
$is_published         = (!is_null($listing_data) and $listing_data['post_status'] == 'publish');
$clean_name           = $listing_data ? clean_str_for_doublequotes($listing_data["name"])        : null;
$clean_description    = $listing_data ? clean_str_for_doublequotes($listing_data["description"]) : null;
$clean_city           = $listing_data ? clean_str_for_doublequotes($listing_data["city"])        : null;
$clean_state          = $listing_data ? clean_str_for_doublequotes($listing_data["state"])       : null;
$verified_venue_ids   = $listing_data ? $listing_data['venues_played_verified']                  : [];
$unverified_venue_ids = $listing_data ? $listing_data['venues_played_unverified']                : [];
$categories           = get_terms_decoded('mcategory', 'names');
$genres               = get_terms_decoded('genre', 'names');
$subgenres            = get_terms_decoded('subgenre', 'names');
$instrumentations     = get_terms_decoded('instrumentation', 'names');
$settings             = get_terms_decoded('setting', 'names');
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
    <div class="sidebar collapsed">
        <?php echo get_template_part('template-parts/account/sidebar', '', [
            'collapsible' => true
        ]); ?>
    </div>
</div>


<div class="lg:container min-h-[500px]"
    x-data="{
        showImageEditPopup:     false,
        showStagePlotPopup:     false,
        showYoutubeLinkPopup:   false,
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
        allVenuesPlayed:         <?php if (!empty($all_venues_played))                  { echo clean_arr_for_doublequotes($all_venues_played);                  } else { echo '[]'; } ?>,
        verifiedVenueIds:        <?php if (!empty($verified_venue_ids))                 { echo clean_arr_for_doublequotes($verified_venue_ids);                 } else { echo '[]'; } ?>,
        unverifiedVenueIds:      <?php if (!empty($unverified_venue_ids))               { echo clean_arr_for_doublequotes($unverified_venue_ids);               } else { echo '[]'; } ?>,
        youtubeVideoData:        <?php if (!empty($listing_data["youtube_video_data"])) { echo clean_arr_for_doublequotes($listing_data["youtube_video_data"]); } else { echo '[]'; } ?>,
        pVideoIds:               <?php if (!empty($listing_data["youtube_video_ids"]))  { echo clean_arr_for_doublequotes($listing_data["youtube_video_ids"]);  } else { echo '[]'; } ?>,
        orderedImageData: {
            'cover_image': [
                {
                    'image_id':      'cover_image',
                    'attachment_id': '<?php if (!empty($listing_data['thumbnail_id']))        { echo $listing_data['thumbnail_id'];                                                } else { echo ''; } ?>',
                    'url':           '<?php if (!empty($listing_data['thumbnail_url']))       { echo $listing_data['thumbnail_url'];                                               } else { echo ''; } ?>',
                    'filename':      '<?php if (!empty($listing_data['thumbnail_filename']))  { echo $listing_data['thumbnail_filename'];                                          } else { echo ''; } ?>',
                    'mediatags':      <?php if (!empty($listing_data["thumbnail_terms"]))     { echo clean_arr_for_doublequotes($listing_data["thumbnail_terms"]);                 } else { echo '[]'; } ?>,
                },
            ],
            'listing_images':         <?php if (!empty($listing_data["listing_images_data"])) { echo clean_arr_for_doublequotes($listing_data["listing_images_data"]);               } else { echo '[]'; } ?>,
            'stage_plots':            <?php if (!empty($listing_data["stage_plots_data"]))    { echo clean_arr_for_doublequotes($listing_data["stage_plots_data"]);                  } else { echo '[]'; } ?>,
        },

        getListingLocation() { return this.pCity && this.pState ? `${this.pCity}, ${this.pState}` : this.pCity || this.pState ? this.pCity || this.pState || '' : 'City, State'; },
        submitButtons: [$refs.updateBtnTop, $refs.updateBtnBottom, $refs.saveDraftBtnTop, $refs.saveDraftBtnBottom, $refs.publishBtnTop, $refs.publishBtnBottom ],

        cropper:                    null,
        showImageProcessingSpinner: false,
        _initCropper(displayElement, imageType, imageId)                { initCropper(this, displayElement, imageType, imageId, this.submitButtons); },
        _initCropperFromFile(event, displayElement, imageType, imageId) { initCropperFromFile(this, event, displayElement, imageType, imageId, this.submitButtons); },

        currentImageId: 'cover_image',
        currentYtIndex:  -1,
        _getImageData(imageType, imageId)                             { return getImageData(this, imageType, imageId); },
        _toggleImageTerm(imageType, imageId, term)                    { toggleImageTerm(this, imageType, imageId, term); },
        _toggleYoutubeLinkTerm(index, term)                           { toggleYoutubeLinkTerm(this, index, term); },
        _addImage(imageType, imageId, imageData)                      { addImage(this, imageType, imageId, imageData); },
        _removeImage(imageType, imageId)                              { removeImage(this, imageType, imageId); },
        _reorderImage(imageType, imageId, newPosition)                { reorderImage(this, imageType, imageId, newPosition); },
        _getAllMediatags()                                            { return getAllMediatags(this); },
        _updateImage(imageType, imageId, url, file, wasCropped)       { updateImage(this, imageType, imageId, url, file, wasCropped); },
        _updateFileInputs()                                           { updateFileInputs(this); },
        _updateAttachmentIds(attachmentIds)                           { updateAttachmentIds(this, attachmentIds); },

        _addYoutubeUrl(input)    { addYoutubeUrl(this, input); },
        _removeYoutubeUrl(index) { removeYoutubeUrl(this, index); },
    }"
    x-on:updateimageids.window="_updateAttachmentIds($event.detail)"
>
    <form id="listing-form" action="/wp-json/v1/listings" enctype="multipart/form-data" class="flex flex-col gap-4"
        hx-post="<?php echo site_url('wp-html/v1/listings'); ?>"
        hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
        hx-target="#result"
        hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
        hx-indicator=".htmx-submit-button"
    >
        <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">
            <div class="col-span-12 lg:col-span-6 relative z-0">

                <div class="mx-auto" style="max-width: 48rem">


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
                                'instance'     => 'Bottom',
                            ]); ?>
                        </div>

                        <div id="result"></div>

                    </div>
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
                        <?php echo get_template_part('template-parts/listing-page/hero', '', array(
                            'instance'          => 'listing-form',
                            'genres'            => $genres,
                        )); ?>

                        <!-- Content -->
                        <?php echo get_template_part('template-parts/listing-page/content', '', array(
                            'instance'          => 'listing-form',
                            'categories'        => $categories,
                            'genres'            => $genres,
                            'subgenres'         => $subgenres,
                            'instrumentations'  => $instrumentations,
                            'settings'          => $settings,
                        )); ?>
                    </div>

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
                            'youtube_video_ids'             => $listing_data ? $listing_data['youtube_video_ids']      : [],
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
        <div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showImageEditPopup" x-cloak>
            <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

                <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;" x-on:click.away="showImageEditPopup = false">

                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-bold text-25 w-full">Crop your image</h4>
                        <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showImageEditPopup = false"/>
                    </div>
                    <div class="grid sm:grid-cols-2">
                        <div class="my-4 max-h-[600px]" >
                            <img class="block max-w-full" x-ref="cropperDisplay" />
                            <div class="flex h-4" x-show="showImageProcessingSpinner" x-cloak>
                                <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                                <span>Processing image...</span>
                            </div>
                        </div>
                        <div class="sm:px-4 md:px-10 py-4">
                            <h5 class="font-bold text-18 mb-3">Link search terms</h5>
                            <p class="mb-4 text-16">Tag your images with the appropriate search terms to help us show the most appropriate media to buyers.</p>
                            <input type="hidden" name="mediatags" x-bind:value="_getAllMediatags()">

                            <template x-for="imageType in ['cover_image', 'listing_images']" :key="imageType">
                                <template x-for="data in orderedImageData[imageType]" :key="data.image_id">
                                    <span x-show="data.image_id == currentImageId">
                                        <p class="text-14 text-grey mb-2">
                                            <span x-text="_getImageData(imageType, data.image_id)?.mediatags.length"></span>/
                                            <span x-text="categoriesCheckboxes.length + instCheckboxes.length + settingsCheckboxes.length"></span>
                                            <span> terms selected</span>
                                        </p>

                                        <div class="overflow-y-scroll max-h-[300px]">
                                            <?php $term_groups = [
                                                [ 'title' => 'Categories',      'selected' => 'categoriesCheckboxes', 'all' => $categories,       ],
                                                [ 'title' => 'Instrumentation', 'selected' => 'instCheckboxes',       'all' => $instrumentations, ],
                                                [ 'title' => 'Settings',        'selected' => 'settingsCheckboxes',   'all' => $settings,         ],
                                                //[ 'title' => 'Genres',          'selected' => 'genresCheckboxes',     'all' => $genres,           ],
                                                //[ 'title' => 'Subgenres',       'selected' => 'subgenresCheckboxes',  'all' => $subgenres,        ],
                                            ];
                                            foreach ($term_groups as $group) { ?>
                                                <div x-show="<?php echo $group['selected']; ?>.length > 0">
                                                    <h4 class="text-16 mb-3"><?php echo $group['title']; ?></h4>
                                                    <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                                        <?php foreach ($group['all'] as $term) { ?>
                                                            <div class="w-fit cursor-pointer"
                                                                x-show="<?php echo $group['selected']; ?>.includes('<?php echo clean_str_for_doublequotes($term); ?>') ||
                                                                        _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>')"
                                                                x-cloak
                                                                x-on:click="_toggleImageTerm(imageType, data.image_id, '<?php echo clean_str_for_doublequotes($term); ?>')"
                                                            >
                                                                <div class="flex items-center border border-black/20 px-3 h-7 rounded-full" :class="{
                                                                    'bg-yellow-40':     _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'font-bold':        _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'border':          !_getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'border-black/20': !_getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                }">
                                                                    <span class="text-14 w-fit"><?php echo $term; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </span>
                                </template>
                            </template>

                        </div>
                    </div>
                </div>


                <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
                    <span class="text-16">Add more search terms to your listing to see more options.</span>
                    <button class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showImageEditPopup = false">Apply</button>
                </div>

            </div>
        </div>

        <!-- Stage Plot popup -->
        <div class="px-4 popup-wrapper pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showStagePlotPopup" x-cloak>
            <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

            <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;" x-on:click.away="showStagePlotPopup = false">

                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-bold text-25 w-full">Add a stage plot image</h4>
                        <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showStagePlotPopup = false"/>
                    </div>
                    <div>
                        <div class="mb-6 grid sm:grid-cols-2 gap-2">
                            <!--
                            <div class="aspect-4/3 w-48 border border-black/20 mx-auto">
                                <img class="h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/stage-plot.jpg' ;?>">
                            </div>
                            <div class="max-w-1/2 break-all text-center flex items-center flex-col justify-center gap-4 px-4 text-center text-grey">
                                stage-plot-1.jpg
                            </div>
                            -->
                            <!--<div class="aspect-4/3 w-48 border border-black/20 mx-auto">-->
                            <div class="my-4 max-h-[600px]" >
                                <img x-ref="stagePlotCropperDisplay" />
                                <div class="flex h-4" x-show="showImageProcessingSpinner" x-cloak>
                                    <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                                    <span>Processing image...</span>
                                </div>
                            </div>
                            <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                                <div class="max-w-1/2 break-all text-center flex items-center flex-col justify-center gap-4 px-4 text-center text-grey"
                                    x-show="data.image_id == currentImageId"
                                    x-text="_getImageData('stage_plots', data.image_id)?.filename">
                                </div>
                            </template>
                        </div>
                        <div class="border-t border-black/20 -mx-6 pt-4 px-6">
                            <label class="mb-1 inline-block">Caption</label>
                            <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                                <input type="text" name="stage_plot_caption"
                                    x-show="data.image_id == currentImageId"
                                    x-bind:value="_getImageData('stage_plots', data.image_id)?.caption"
                                    x-on:change="_getImageData('stage_plots', data.image_id).caption = $event.target.value"
                                />
                            </template>
                        </div>

                    </div>
                </div>


                <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
                    <span class="text-16">Let potential clients know what this is.</span>
                    <button class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showStagePlotPopup = false">Apply</button>
                </div>

            </div>
        </div>

        <!-- Youtube link popup -->
        <div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showYoutubeLinkPopup" x-cloak>
            <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

            <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;" x-on:click.away="showYoutubeLinkPopup = false">

                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-bold text-25 w-full" x-show="currentYtIndex < 0" x-cloak>Add a YouTube video</h4>
                        <h4 class="font-bold text-25 w-full" x-show="currentYtIndex >= 0" x-cloak>Edit YouTube video settings</h4>
                        <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showYoutubeLinkPopup = false"/>
                    </div>
                    <div class="min-h-10">
                        <?php
                        echo get_template_part('template-parts/global/toasts/success-toast', '', ['customEvent' => 'success-toast-youtube-link']);
                        echo get_template_part('template-parts/global/toasts/error-toast',   '', ['customEvent' => 'error-toast-youtube-link']);
                        ?>
                    </div>
                    <div class="sm:min-w-[500px] mb-10" x-show="currentYtIndex < 0" x-cloak>
                        <label class="mb-1 inline-block">Video url</label>
                        <div class="relative">
                            <input class="w-full mb-2 !pr-16" type="text" placeholder="https://"
                                x-ref="ytInput"
                                x-on:keydown.enter="$event.preventDefault(); _addYoutubeUrl($el)">
                            <button type="button" class="absolute top-2 right-2 w-fit rounded text-12 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black text-grey hover:text-black"
                                x-on:click="_addYoutubeUrl($refs.ytInput)"
                            >Add +</button>
                        </div>
                    </div>
                    <template x-for="(videoData, index) in youtubeVideoData" :key="index">
                        <div class="grid sm:grid-cols-2" x-show="currentYtIndex >= 0 && index == currentYtIndex" x-cloak>
                            <div class="my-4 max-h-[600px]" >
                                <img class="w-full" x-bind:src="`https://img.youtube.com/vi/${videoData.video_id}/mqdefault.jpg`">
                                <div class="mt-6">
                                    <label class="mb-1 inline-block">Video Url</label>
                                    <div class="text-14 text-grey break-words whitespace-normal" x-text="videoData.url"></div>
                                </div>
                                <div class="mt-6" x-data="{
                                    calcStartTime() { return parseInt($refs.startMinute?.value || 0) * 60 + parseInt($refs.startSecond?.value || 0); },
                                }">
                                    <label class="mb-1 inline-block">Start time</label><br>
                                    <label class="mb-1 inline-block text-14">Minute</label>
                                    <input class="w-full" type="number" min="0" x-ref="startMinute"
                                        x-on:change="youtubeVideoData[index].start_time = calcStartTime()"
                                        x-bind:value="Math.floor(videoData.start_time / 60)"
                                    />
                                    <label class="mb-1 inline-block text-14">Second</label>
                                    <input class="w-full" type="number" min="0" max="59" x-ref="startSecond"
                                        x-on:change="youtubeVideoData[index].start_time = calcStartTime()"
                                        x-bind:value="videoData.start_time % 60"
                                    />
                                </div>
                            </div>
                            <div class="sm:px-4 md:px-10 py-4">
                                <div class="-mx-6 pb-2 sm:pb-0 px-6">
                                    <h5 class="font-bold text-18 mb-4">Link search terms</h5>
                                    <p class="mb-4 text-16">Tag your videos with the appropriate search terms to help us show the most appropriate media to buyers.</p>
                                    <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                        <div class="overflow-y-scroll max-h-[300px]">
                                            <?php $term_groups = [
                                                [ 'title' => 'Categories',      'selected' => 'categoriesCheckboxes', 'all' => $categories,       ],
                                                [ 'title' => 'Instrumentation', 'selected' => 'instCheckboxes',       'all' => $instrumentations, ],
                                                [ 'title' => 'Settings',        'selected' => 'settingsCheckboxes',   'all' => $settings,         ],
                                                [ 'title' => 'Genres',          'selected' => 'genresCheckboxes',     'all' => $genres,           ],
                                                [ 'title' => 'Subgenres',       'selected' => 'subgenresCheckboxes',  'all' => $subgenres,        ],
                                            ];
                                            foreach ($term_groups as $group) { ?>
                                                <div x-show="<?php echo $group['selected']; ?>.length > 0">
                                                    <h4 class="text-16 mb-3"><?php echo $group['title']; ?></h4>
                                                    <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                                        <?php foreach ($group['all'] as $term) { ?>
                                                            <div class="w-fit cursor-pointer"
                                                                x-show="<?php echo $group['selected']; ?>.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak
                                                                x-on:click="_toggleYoutubeLinkTerm(index, '<?php echo clean_str_for_doublequotes($term); ?>')"
                                                            >
                                                                <div class="flex items-center border border-black/20 px-3 h-7 rounded-full" :class="{
                                                                    'bg-yellow-40':     youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'font-bold':        youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'border':          !youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                    'border-black/20': !youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                                }">
                                                                    <span class="text-14 w-fit"><?php echo $term; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <p class="text-14 text-grey mb-2">
                                            <span x-text="youtubeVideoData[index]?.mediatags.length"></span>/
                                            <span x-text="categoriesCheckboxes.length + genresCheckboxes.length + subgenresCheckboxes.length + instCheckboxes.length + settingsCheckboxes.length"></span>
                                            <span> terms selected</span>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4" x-show="currentYtIndex >= 0" x-cloak>
                    <span class="text-16">Add more search terms to your listing to see more options.</span>
                    <button class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showYoutubeLinkPopup = false">Apply</button>
                </div>
            </div>
        </div>

    </form>

</div>


<?php
get_footer();
