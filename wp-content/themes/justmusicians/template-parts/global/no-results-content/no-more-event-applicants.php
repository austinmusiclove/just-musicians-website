<div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

    <div class="pb-32 relative">

        <span class="text-22 block text-center mb-2">Looks like you've reached the <span class="text-yellow">end of the tail.</span></span>
        <p class="text-20 mb-4">Still looking for your hired gun?</p>

        <button type="button"
            class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
            hx-get="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/inquiry-suggestions/'); ?>"
            hx-target="#applicant-suggestion-results"
            hx-indicator="#applicants-spinner-bottom"
            hx-trigger="click"
        >
            Get Suggestions
        </button>

    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
