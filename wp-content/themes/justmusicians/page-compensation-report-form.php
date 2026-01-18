<?php
/**
 * The template for the compensation report form
 *
 */

get_header();


?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10"> <!--Look here -->
        <div class="sm:col-span-7 pr-8 sm:pr-0">
            <h1 class="font-bold text-32 md:text-40 mb-6">Compensation Report Form</h1>
            <p>This is an anonymous report. Your name, performance date, and any other identifiable informaiton will not be shared publically to ensure anonimity.</p>
        </div>

    </div>
</header>


<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">

    <!-- Form -->
    <form class="flex flex-col col lg:col-span-7 mb-8 lg:mb-0 gap-8"
        hx-post="<?php echo site_url('wp-html/v1/compensation-reports'); ?>"
        hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
        hx-target="#result"
        hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
        hx-indicator=".htmx-submit-button"
    >

        <!-- Venue -->
        <?php echo get_template_part('template-parts/compensation-report-form/venue-input', '', []); ?>

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

get_footer();
