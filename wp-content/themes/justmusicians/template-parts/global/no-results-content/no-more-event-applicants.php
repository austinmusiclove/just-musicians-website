<div x-show="!showSuggestions" x-collapse.duration.300ms class="overflow-hidden">

    <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

        <div class="relative pb-32">

            <span class="text-22 block text-center mb-2">Looks like you've reached the <span class="text-yellow">end of the tail.</span></span>
            <p class="text-20 mb-4">Still looking for your hired gun?</p>

            <button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
                hx-get="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/inquiry-suggestions/'); ?>"
                hx-target="#applicant-results"
                hx-swap="beforeend"
                hx-indicator="#suggestion-button-content-nmr"
                hx-trigger="click"
            >
                <span id="suggestion-button-content-nmr" class="flex justify-center">
                    <span class="htmx-indicator-component-block-replace">Get Suggestions</span>
                    <span class="htmx-indicator-component-block">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                    </span>
                </span>
            </button>

        </div>

        <img class="w-40 absolute bottom-0 left-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
        <img class="w-40 absolute bottom-0 right-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

    </div>

</div>
