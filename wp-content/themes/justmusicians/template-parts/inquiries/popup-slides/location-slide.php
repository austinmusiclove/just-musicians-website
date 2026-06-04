<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showLocationSlide" x-cloak>



    <div class="flex items-center gap-2 mb-3">
        <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>
        <span class="inset-0 flex items-center justify-center htmx-indicator inquiry-location-active-search-spinner">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>

    <p><span class="text-red">* </span>Location</p>
    <div class="relative flex items-center">
        <img class="h-4 absolute left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
        <input id="inquiry-location-input" class="w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none" type="text" name="location" autocomplete="off" placeholder="Start typing your city.."
            :class="{ 'shake': shakeElements.has('inquiry-location-input') }"
            x-ref="inquiryLocationInputElm"
            x-model="inquiryLocationInput"
            x-on:focus="showInquiryLocationSearchOptions = true; inquiryLocationInput = '';"
            x-on:click.away="showInquiryLocationSearchOptions = false; $el.value = inquiryLocation;"
            hx-get="<?php echo site_url('/wp-html/v1/if-location-search-options/'); ?>"
            hx-trigger="input changed delay:300ms"
            hx-target="#inquiry-location-active-search-results"
            hx-indicator=".inquiry-location-active-search-spinner"
            x-on:keydown.enter.prevent
        />
         <div id="inquiry-location-active-search-results" x-show="showInquiryLocationSearchOptions" x-cloak>
             <?php echo get_template_part('template-parts/search/if-location-search-state-1', '', array()); ?>
         </div>
    </div>
    <input type="hidden" name="inquiry_city" x-model="inquiryCity" />
    <input type="hidden" name="inquiry_state" x-model="inquiryState" />
    <input type="hidden" name="inquiry_zip_code" x-model="inquiryZipCode" />
    <input type="hidden" name="inquiry_lat" value="" x-model="inquiryLat" />
    <input type="hidden" name="inquiry_lng" value="" x-model="inquiryLng" />

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('date')">Back</button>
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="!inquiryLocation" x-cloak
            x-on:click="_emphasizeElm($refs.inquiryLocationInputElm, 'inquiry-location-input')"
        >Next</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
            x-show="inquiryLocation" x-cloak
            x-on:click="_showInquirySlide('budget')"
        >Next</button>
    </div>

</div>
