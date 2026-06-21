<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showLocationSlide" x-cloak>



    <div class="flex items-start gap-2 mb-3">
        <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>
        <span id="inquiry-location-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>

    <p><span class="text-red">* </span>Location</p>
    <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
        'container_class' => 'relative flex items-center',
        'image_class'     => 'absolute h-4 left-2',
        'image_file'      => 'location.svg',
        'id'              => 'inquiry-location-input',
        'input_class'     => 'h-full w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none',
        'input_name'      => 'location',
        'placeholder'     => 'Start typing your city or postal code..',
        'autocomplete'    => 'off',
        'required'        => true,
        'input_var'       => 'inquiryLocationInput',
        'selected_var'    => 'inquiryLocation',
        'show_var'        => 'showInquiryLocationSearchOptions',
        'x_class'         => "{ 'shake': shakeElements.has('inquiry-location-input') }",
        'htmx_path'       => '/wp-html/v1/location-search-options/',
        'spinner_id'      => 'inquiry-location-active-search-spinner',
        'update_func'     => '_updateInquiryLocation',
        'state_1_msg'     => 'Start typing a city or postal code..',
    ]); ?>
    <input type="hidden" name="event_city" x-model="inquiryCity" />
    <input type="hidden" name="event_state" x-model="inquiryState" />
    <input type="hidden" name="event_zip_code" x-model="inquiryZipCode" />
    <input type="hidden" name="event_lat" value="" x-model="inquiryLat" />
    <input type="hidden" name="event_lng" value="" x-model="inquiryLng" />

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
