<?php
/**
 * The template for the compensation report form
 */
get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10"> <!--Look here -->
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6">Contribute to the Live Musician Earnings Database</h1>
            <p class="text-18">
                This is a community project to track how much music venues pay musicians.
                <br><br>
                All entries are completely anonymous. We will never share names, show dates, or other personal details. That information is only used to make sure the entry is real.
                Each entry helps us publish useful facts about how you can expect to be paid by music venues as a musician.
            </p>
        </div>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">

    <!-- Form -->
    <form class="flex flex-col col lg:col-span-7 mb-8 lg:mb-0 gap-8"
        hx-ext="response-targets"
        hx-post="<?php echo site_url('wp-html/v1/compensation-reports'); ?>"
        hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
        hx-target="#result"
        hx-target-error="#result"
        hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
        hx-indicator=".htmx-submit-button"
        hx-on::after-request="
            if (event.detail.xhr.status === 200 && event.detail.xhr.responseURL.includes('wp-html/v1/compensation-reports')) {
                this.reset();
                window.dispatchEvent(new CustomEvent('clear-form'));
            }"
    >

        <!-- Venue -->
        <?php echo get_template_part('template-parts/compensation-report-form/venue-input', '', []); ?>

        <!-- Earnings Input Group -->
        <?php echo get_template_part('template-parts/compensation-report-form/earnings-inputs', '', []); ?>

        <!-- Performance Input Group -->
        <?php echo get_template_part('template-parts/compensation-report-form/performance-inputs', '', []); ?>

        <!-- Review Input -->
        <?php //echo get_template_part('template-parts/compensation-report-form/review-input', '', []); ?>

        <!-- Submit button -->
        <?php echo get_template_part('template-parts/compensation-report-form/submit-button', '', []); ?>

        <div id="result"></div>

    </form>

    <!-- Sidebar -->
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            )); ?>
        </div>
    </div>

</div>

<?php

// Show login form to users who are not logged in
if (!is_user_logged_in()) { ?>
    <span x-init="showLoginModal = true; signupModalMessage = 'Sign up to contribute'; loginModalMessage = 'Sign in to contribute';"></span>
<?php }

get_footer();
