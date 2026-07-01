<?php
/**
 * Template for musician application form
 *
 * @package JustMusicians
 */

$application_id = get_query_var('application-id');
$title = get_post_meta($application_id, 'title', true);
$description = get_post_meta($application_id, 'description', true);

$current_user_id = get_current_user_id();
$user_listings   = $current_user_id ? get_user_listings($current_user_id) : [];

get_header();
?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container pt-20 md:pt-32 pb-6 md:pb-12">

            <?php if (!$application_id || !$title) { ?>

                <p class="text-16 text-black/60">Application not found.</p>

            <?php } ?>

                <h1 class="font-bold text-25 mb-4"><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80"><?php echo wpautop(esc_html($description)); ?></div>
                <?php } ?>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-up-to-access', '', [ 'message' => 'submit an application' ]); ?>

                <?php } else { ?>

                <form class="flex flex-col gap-4" x-data="{
                    hasListings: <?php echo count($user_listings) > 0 ? 'true' : 'false'; ?>,
                    createNewListing: false,
                    showImageEditPopup:     false,
                    showStagePlotPopup:     false,
                    showYoutubeLinkPopup:   false,
                    showZipSearchOptions:   false,
                    pName:                  '',
                    pDescription:           '',
                    pCity:                  '',
                    pState:                 '',
                    pZipCode:               '',
                    zipCodeInput:           '',
                    fullLocation:           '',
                    pBio:                   '',
                    pEmail:                 '',
                    pPhone:                 '',
                    pInstagramHandle:       '',
                    pInstagramUrl:          '',
                    pTiktokHandle:          '',
                    pTiktokUrl:             '',
                    pXHandle:               '',
                    pXUrl:                  '',
                    pWebsite:               '',
                    pFacebookUrl:           '',
                    pYoutubeUrl:            '',
                    pBandcampUrl:           '',
                    pSpotifyArtistUrl:      '',
                    pSpotifyArtistId:       '',
                    pAppleMusicArtistUrl:   '',
                    pSoundcloudUrl:         '',
                    pThumbnailSrc:          '',
                    ensembleSizeCheckboxes: [],
                    genresCheckboxes:       [],
                    youtubeVideoData:       [],
                    orderedImageData: {
                        'cover_image': [
                            {
                                'image_id':      'cover_image',
                                'attachment_id': '',
                                'url':           '',
                                'filename':      '',
                                'mediatags':     [],
                                'loading':       false,
                                'worker':        null,
                            },
                        ],
                        'listing_images':        [],
                        'stage_plots':           [],
                    },
                    listingFormUpdateLocation(location) { this.fullLocation = location.label; this.zipCodeInput = location.label; this.pZipCode = location.postal_code; this.pCity = location.city; this.pState = location.state; },

                    cropper:                    null,
                    showCropperDisplay:         true,
                    popupImageSpinner: false,
                    _initCropper(displayElement, imageType, imageId)                { initCropper(this, displayElement, imageType, imageId, this._getImageData(imageType, imageId).url, false); },
                    _initCropperFromFile(event, displayElement, imageType, imageId) { initCropperFromFile(this, event, displayElement, imageType, imageId); },

                    currentImageId: 'cover_image',
                    currentYtIndex:  -1,
                    _getImageData(imageType, imageId)                             { return getImageData(this, imageType, imageId); },
                    _removeImage(imageType, imageId)                              { removeImage(this, imageType, imageId); },
                    _reorderImage(imageType, imageId, newPosition)                { reorderImage(this, imageType, imageId, newPosition); },
                    _updateFileInputs(imageType)                                  { updateFileInputs(this, imageType); },
                    _updateAttachmentIds(attachmentIds)                           { updateAttachmentIds(this, attachmentIds); },

                    _addYoutubeUrl(input)    { addYoutubeUrl(this, input); },
                    _removeYoutubeUrl(index) { removeYoutubeUrl(this, index); },
                }">

                    <!-- Listing Dropdown -->
                    <?php if ($current_user_id and count($user_listings) > 0) { ?>
                    <?php get_template_part('template-parts/applications/musician-application/listing-dropdown', '', [
                        'listings' => $user_listings,
                    ]); ?>
                    <?php } ?>

                    <!-- Listing Form -->
                    <div x-show="createNewListing || !hasListings" x-cloak>
                        <?php get_template_part('template-parts/applications/musician-application/listing-form', '', []); ?>
                    </div>

                    <!-- Message Input -->
                    <div class="has-border p-0">
                        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
                            <span class="font-bold">Personalized Message</span>
                        </label>
                        <textarea id="message" name="message" placeholder="Here's your chance to send the application reviewer a personalized message" class="w-full h-32 !border-0"></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2 w-full sm:w-fit disabled:opacity-70 disabled:hover:bg-black/40"
                        x-bind:disabled="!loggedIn"
                    >Submit Application</button>


                    <!-- Media modals -->
                    <?php echo get_template_part('template-parts/listing-form/popups/image-edit-popup', '', []); ?>
                    <?php echo get_template_part('template-parts/listing-form/popups/stage-plot-popup', '', []); ?>
                    <?php echo get_template_part('template-parts/listing-form/popups/youtube-link-popup', '', []); ?>

                </form>

            <?php } ?>

        </div>
    </div>
</div>

<?php
get_footer();
