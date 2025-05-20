<div data-popup="quote" class="popup-wrapper pt-28 md:pt-0 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showInquiryModal" x-cloak>
    <!-- TODO need to go to slide 5 if not done or just close it all if done and if got o slide 5 need to remember what slide they were on to return to it -->
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="_tryExitInquiryModal()"
    ></div>

    <div class="bg-white p-8 md:p-20 relative w-full h-full md:w-auto md:h-auto flex items-center justify-center" style="max-width: 780px;">

        <!-- Progress tracker -->
        <div class="progress-tracker bg-yellow h-2 absolute top-0 left-0 transition-all duration-500"
            x-bind:style="`width: ${inquiryProgress}%`"
        ></div>

        <!-- X button -->
        <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer"
            src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="_tryExitInquiryModal()"
        />


        <form x-ref="inquiryForm"
            hx-post="/wp-html/v1/inquiries/"
            hx-target="#inquiry-result"
            hx-ext="disable-element" hx-disable-element=".inquiry-submit-button"
        >
            <input type="hidden" name="inquiry_listing" x-model="inquiryListing" x-ref="inquiryListingInput" />
            <?php echo get_template_part('template-parts/global/form-quote/date-slide',            '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/location-slide',        '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/duration-slide',        '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/genre-slide',           '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/performers-slide',      '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/equipment-slide',       '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/details-slide',         '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/competing-quote-slide', '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/discard-slide',         '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/thank-you-slide',       '', []); ?>
            <?php echo get_template_part('template-parts/global/form-quote/error-slide',           '', []); ?>
        </form>


    </div>
</div>
