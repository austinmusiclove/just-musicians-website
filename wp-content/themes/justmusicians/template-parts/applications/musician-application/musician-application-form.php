<form class="flex flex-col gap-8" enctype="multipart/form-data" novalidate data-testid="musician-application-form"
    x-show="showApplication" x-cloak x-ref="listingForm" x-init="$watch('listingId', () => htmx.process($el))"
    <?php if ($args['demo']) { ?>
    x-bind:hx-post="'<?php echo site_url('/wp-html/v1/applications/' . $args['application_id']); ?>' + (listingId ? `/listings/${listingId}/submit/demo/` : '/submit/demo/')"
    <?php } else { ?>
    x-bind:hx-post="'<?php echo site_url('/wp-html/v1/applications/' . $args['application_id']); ?>' + (listingId ? `/listings/${listingId}/submit/` : '/submit/')"
    <?php } ?> hx-trigger="submitapplication" hx-target="#submit-application-result"
    hx-indicator="#submit-button-content" x-data="{
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
        onListingSelect(option) {
            listingId = option.value;
            message = '';
            if (!listingId) {
                createNewListing = true;
                eventAvailability = {};
            } else {
                createNewListing = false;
                eventAvailability = {};
                const lp = savedProposals[listingId] || {};
                Object.keys(lp).forEach(eid => { eventAvailability[eid] = lp[eid].availability; });
            }
        },

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
    <?php if (count($args['user_listings']) > 0) { ?>
    <?php get_template_part('template-parts/applications/musician-application/listing-dropdown', '', [
        'listings'           => $args['user_listings'],
        'parent_select_func' => 'onListingSelect',
    ]); ?>
    <?php } ?>

    <!-- Listing Form -->
    <div x-show="createNewListing || !hasListings" x-cloak>
        <?php get_template_part('template-parts/applications/musician-application/listing-form', '', []); ?>
    </div>

    <!-- Min Guarantee and Draw Estimate-->
    <div class="flex flex-col gap-2 min-w-0 border border-black/20 rounded bg-yellow-10/50">
        <label class="block bg-yellow-20 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">More Details</span>
        </label>
        <fieldgroup class="grid sm:grid-cols-2 gap-2 p-4">
            <div>
                <label class="mb-1 inline-block" for="min_guarantee">Min Guarantee</label><br>
                <div class="relative">
                    <span
                        class="absolute inset-y-0 left-0 pl-3 flex pr-1 items-center text-grey pointer-events-none">$</span>
                    <input class="!pl-8 w-full px-3 py-2" type="number" id="min_guarantee" name="min_guarantee" min="0"
                        title="How many people can you guarantee will show up?" x-model="">
                </div>
                <input type="hidden" id="min_guarantee" name="min_guarantee" x-init="" x-model="">
            </div>
            <div>
                <label class="mb-1 inline-block" for="draw_estimate">Draw Estimate</label><br>
                <div class="relative">
                    <span
                        class="absolute inset-y-0 right-0 pr-3 flex pl-1 items-center text-grey pointer-events-none">people</span>
                    <input class="!pr-20 w-full px-3 py-2" type="number" id="draw_estimate" name="draw_estimate" min="0"
                        title="How many people can you guarantee will show up?" x-model="">
                </div>
                <input type="hidden" id="draw_estimate" name="draw_estimate" x-init="" x-model="">
            </div>
    </div>

    <!-- Application Submission Inputs -->
    <div class="has-border p-0" data-testid="application-submission-inputs">
        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Personalized Message</span>
        </label>
        <textarea name="applicant_message" class="w-full h-32 !border-0"
            placeholder="Here's your chance to send the application reviewer a personalized message"
            x-model="message"></textarea>
    </div>
    <input type="hidden" name="application_id" value="<?php echo $args['application_id']; ?>" />
    <input type="hidden" name="status" value="active" />


    <!-- Availability -->
    <?php get_template_part('template-parts/applications/musician-application/availability-inputs', '', [ 'events' => $args['events'] ]); ?>

    <!-- Submit -->
    <button type="button"
        class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-2 py-2 w-full sm:w-fit disabled:opacity-70 disabled:hover:bg-black/40"
        x-bind:disabled="hasListings && !listingId && !createNewListing" x-on:click=" <?php // Skip front end validation when user is not creating a listing ?>
            if (!createNewListing) {
                $dispatch('submitapplication');
            } else if ($refs.listingForm.reportValidity()) {
                $dispatch('submitapplication');
            }
        ">
        <span id="submit-button-content">
            <span class="htmx-indicator-component-block-replace">Submit Application</span>
            <span class="htmx-indicator-component-block mx-2 my-1">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </span>
    </button>

    <!-- Media modals -->
    <?php echo get_template_part('template-parts/listing-form/popups/image-edit-popup', '', []); ?>
    <?php echo get_template_part('template-parts/listing-form/popups/stage-plot-popup', '', []); ?>
    <?php echo get_template_part('template-parts/listing-form/popups/youtube-link-popup', '', []); ?>

</form>

<span id="submit-application-result"></span>