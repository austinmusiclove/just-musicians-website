<form class="flex flex-col gap-4" enctype="multipart/form-data"
    x-ref="listingForm"
    x-init="$watch('listingId', () => htmx.process($el))"
    x-bind:hx-post="'<?php echo site_url('/wp-html/v1/applications/' . $application_id); ?>' + (listingId ? `/listings/${listingId}/submit/` : '/submit/')"
    hx-target="#submit-application-result"
    hx-indicator="#submit-button-content"
    x-data="{
        onListingSelect(option) {
            listingId = option.value;
            message = '';
            if (!option.value) {
                createNewListing = true;
                this.pName        = '';
                this.pDescription = '';
                this.zipCodeInput = '';
                this.fullLocation = '';
            } else {
                createNewListing = false;
                this.pName        = 'ignore'; // Set to bypass front end input require for this field; value is ignored in the back end when listing id is set
                this.pDescription = 'ignore'; // Set to bypass front end input require for this field; value is ignored in the back end when listing id is set
                this.zipCodeInput = 'ignore'; // Set to bypass front end input require for this field; value is ignored in the back end when listing id is set
                this.fullLocation = 'ignore'; // Set to bypass front end input require for this field; value is ignored in the back end when listing id is set
            }
        },
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
    }"
>

    <!-- Listing Dropdown -->
    <?php if ($args['current_user_id'] and count($args['user_listings']) > 0) { ?>
    <?php get_template_part('template-parts/applications/musician-application/listing-dropdown', '', [
        'listings'           => $args['user_listings'],
        'parent_select_func' => 'onListingSelect',
    ]); ?>
    <?php } ?>

    <!-- Listing Form -->
    <div x-show="createNewListing || !hasListings" x-cloak>
        <?php get_template_part('template-parts/applications/musician-application/listing-form', '', []); ?>
    </div>

    <!-- Application Submission Inputs -->
    <div class="has-border p-0">
        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Personalized Message</span>
        </label>
        <textarea name="message" class="w-full h-32 !border-0"
            placeholder="Here's your chance to send the application reviewer a personalized message"
            x-model="message"
        ></textarea>
    </div>
    <input type="hidden" name="application_id" value="<?php echo $application_id; ?>" />
    <input type="hidden" name="status" value="active" />

    <!-- Submit -->
    <button type="submit" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-2 py-2 w-full sm:w-fit disabled:opacity-70 disabled:hover:bg-black/40"
        x-bind:disabled="!loggedIn || (!listingId && !createNewListing)"
    >
        <span id="submit-button-content">
            <span class="htmx-indicator-component-block-replace">Submit Application</span>
            <span class="htmx-indicator-component-block mx-2 my-1">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </span>
    </button>
    <span id="submit-application-result"></span>


    <!-- Media modals -->
    <?php echo get_template_part('template-parts/listing-form/popups/image-edit-popup', '', []); ?>
    <?php echo get_template_part('template-parts/listing-form/popups/stage-plot-popup', '', []); ?>
    <?php echo get_template_part('template-parts/listing-form/popups/youtube-link-popup', '', []); ?>

</form>
