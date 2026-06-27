<div class="slide grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col items-center overflow-y-auto pr-2" x-show="showRequestSlide" x-cloak>


    <h2 class="font-bold font-sun-motter text-20 mb-8">Send and existing event inquiry to <span class="text-yellow" x-text="inquiryListingName"></span>?</h2>

    <div class="w-full space-y-2 min-h-[8rem] max-h-72 overflow-y-auto"
        hx-get="<?php echo site_url('/wp-html/v1/rfp-events/'); ?>"
        hx-trigger="getrfpevents"
        hx-target="#request-slide-results"
        hx-indicator=".request-slide-indicator"
        x-on:requestslide.window="$dispatch('getrfpevents')"
    >
        <span class="request-slide-indicator htmx-indicator-flex justify-center">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
        </span>
        <span id="request-slide-results" class="request-slide-indicator htmx-indicator-block-replace"></span>
    </div>

    <div class="mt-auto pt-6 flex flex-col items-center gap-1">
        <span class="font-bold font-sun-motter text-20">or</span>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2 hover:bg-yellow hover:text-white"
            x-on:click="_showInquirySlide('date')"
        >Create New Event</button>
    </div>

</div>
