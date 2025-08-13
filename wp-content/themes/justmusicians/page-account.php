<?php

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page

$account_settings = get_account_settings();

get_header();

?>

<div class="flex flex-col grow"
    x-data="{
        accountSettings: <?php if ($account_settings != null) { echo clean_arr_for_doublequotes($account_settings); } else { echo 'null'; } ?>,
        imageProcessing: false,
        tmpImage: null,
        cropper: null,
        _initCropper(displayElement)                { initCropper(this, displayElement, this.accountSettings.profile_image.url, [$refs.submitButton], false); },
        _initCropperFromFile(event, displayElement) { initCropperFromFile(this, event, displayElement, [$refs.submitButton]); },
        _closeCropper()                             { closeCropper(this, [$refs.submitButton]); },
    }"
    x-on:updateimageid="accountSettings.profile_image.attachment_id = $event.detail"
>
    <form enctype="multipart/form-data"
        hx-post="<?php echo site_url('wp-html/v1/account-settings'); ?>"
        hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
        hx-target="#result"
        hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
        hx-indicator=".htmx-submit-button"
    >

        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                    </div>
                </div>
                <div class="col sm:col-span-4 md:col-span-3 py-6 md:py-12">


                    <!-- Heading -->
                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <a href="/account"><h1 class="font-bold text-22 sm:text-25">Account Settings</h1></a>
                    </div>


                    <!-- Settings -->
                    <div class="flex flex-col gap-8 md:col-span-6 pb-4">

                        <!-- Display Name -->
                        <?php echo get_template_part('template-parts/account/settings/display-name', '', []); ?>

                        <!-- Profile Image -->
                        <?php echo get_template_part('template-parts/account/settings/profile-image', '', []); ?>

                    </div>


                    <!-- Submit button -->
                    <button type="submit" class="htmx-submit-button my-8 w-fit relative rounded text-14 font-bold py-2 px-3 bg-navy text-white disabled:opacity-50"
                        x-ref="submitButton"
                    >
                        <span class="htmx-indicator-replace">Save Settings</span>
                        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                        </span>
                    </button>


                </div>
            </div>
        </div>

        <span id="result"></span>
    </form>
</div>

<?php
get_footer();
