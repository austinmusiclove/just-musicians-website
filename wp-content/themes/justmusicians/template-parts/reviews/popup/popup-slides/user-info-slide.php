<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] overflow-y-auto pr-2" x-show="showReviewUserInfoSlide" x-cloak x-data="{
    displayNameInput: accountSettings.display_name,
    organizationInput: accountSettings.organization,
    positionInput: accountSettings.position,
    imageProcessing: false,
    tmpImage: null,
    cropper: null,
    _initCropper(displayElement)                { initCropper(this, displayElement, accountSettings.profile_image.url, [$refs.accountSettingsSubmit], false); },
    _initCropperFromFile(event, displayElement) { initCropperFromFile(this, event, displayElement, [$refs.accountSettingsSubmit]); },
    _closeCropper()                             { closeCropper(this, [$refs.accountSettingsSubmit]); },
}"
>
    <!-- Heading -->
    <h2 class="font-bold font-poppins text-20 mb-8">Thanks for writing a review for <span x-text="revieweeName"></span>!</h2>
    <p class="mb-8">Adding your name, title, and organization boosts your review’s credibility and impact while giving the artist meaningful recognition.</p>

    <!-- Author Preview -->
    <figcaption class="flex gap-x-6 mb-8 justify-center">
        <img x-bind:src="accountSettings.profile_image.url" alt="" class="w-12 h-12 rounded-full" />
        <div class="text-16 flex flex-col gap-1 justify-center items-left">
            <div class="font-semibold text-grey" x-text="displayNameInput || 'Display Name'"></div>
            <div class="text-grey" x-text="[positionInput, organizationInput].filter(Boolean).join(' at ') || 'Job Title at Organization'"> </div>
        </div>
    </figcaption>

    <!-- Inputs -->
    <div id="account-settings-inputs" class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 gap-y-1 sm:gap-y-3 items-center">
        <label for="display_name" class="col-span-1">Display Name
            <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'This is the name other users will see when they interact with you. This is not your band or organization name.' ]); ?>
        </label>
        <input class="border border-black/20 rounded h-8 p-2 mb-2 col-span-1 sm:col-span-2" type="text" name="display_name" placeholder="Display Name" x-model="displayNameInput">
        <label for="organization" class="col-span-1">Organization
            <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'The organization you represent. This could be your band name, agency name, venue, etc.' ]); ?>
        </label>
        <input class="border border-black/20 rounded h-8 p-2 mb-2 col-span-1 sm:col-span-2" type="text" name="organization" placeholder="Organization" x-model="organizationInput">
        <label for="position" class="col-span-1">Job Title
            <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Your position at the organization you represent. I.e. Guitarist, Talent Buyer, General Manger, etc.' ]); ?>
        </label>
        <input class="border border-black/20 rounded h-8 p-2 mb-2,col-span-1 sm:col-span-2" type="text" name="position" placeholder="Job Title" x-model="positionInput">
        <div class="flex flex-col gap-4">
            <?php echo get_template_part('template-parts/account/settings/profile-image/label', '', []); ?>
            <?php echo get_template_part('template-parts/account/settings/profile-image/buttons', '', []); ?>
        </div>
        <div class="flex flex-col gap-4 ml-2">
            <?php echo get_template_part('template-parts/account/settings/profile-image/image-display', '', []); ?>
        </div>
        <?php echo get_template_part('template-parts/account/settings/profile-image/hidden-inputs', '', []); ?>
        <input type="hidden" name="is_popup" value="1">
    </div>

    <!-- Submit Button -->
    <div class="account-settings-indicator mt-4 flex flex-row justify-end gap-1">

        <button type="button" class="flex justify-center items-center bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2 w-[82px] h-[40px] disabled:opacity-50 account-settings-submit-button"
            hx-post="<?php echo site_url('wp-html/v1/account-settings'); ?>"
            hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
            hx-encoding="multipart/form-data"
            hx-include="#account-settings-inputs"
            hx-target="#account-settings-result"
            hx-ext="disable-element" hx-disable-element=".account-settings-submit-button"
            hx-indicator=".account-settings-indicator"
            x-ref="accountSettingsSubmit"
        >
            <span class="htmx-indicator-component-block-replace">Submit</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>

    </div>

    <div id="account-settings-result"></div>
</div>
