<div class="flex flex-col gap-2 mb-4" x-data="{
    cityInput: city,
    citySelected: city,
    showCitySearchOptions: false,
    stateInput: state,
    zipCodeInput: zipCode,
    zipCodeSelected: zipCode,
    showZipSearchOptions: false,
    updateEventFormLocation(location) { this.zipCodeSelected = location.postal_code; this.zipCodeInput = location.postal_code; this.citySelected = location.city; this.cityInput = location.city; this.stateInput = location.state; lat = location.lat; lng = location.lng; },
}">
    <div>
        <label class="text-14 font-bold">Address Line 1</label>
        <input type="text" name="event_address_line_1" x-bind:value="addressLine1" class="w-full" placeholder="Street address" />
    </div>
    <div>
        <label class="text-14 font-bold">Address Line 2</label>
        <input type="text" name="event_address_line_2" x-bind:value="addressLine2" class="w-full" placeholder="Apt, suite, etc." />
    </div>
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="flex flex-col justify-end">
            <div class="flex flex-row">
                <label class="text-14 font-bold">City<span class="text-red"> *</span></label>
                <span id="city-active-search-spinner" class="px-2 inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                </span>
            </div>
            <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
                'container_class' => 'relative flex items-center',
                'image_class'     => 'h-4 absolute bottom-3 left-3 opacity-60',
                'image_file'      => 'location-2.svg',
                'id'              => 'edit-event-city',
                'input_class'     => 'has-icon',
                'input_name'      => 'location',
                'placeholder'     => 'City',
                'autocomplete'    => 'location-disabled',
                'required'        => true,
                'input_var'       => 'cityInput',
                'selected_var'    => 'citySelected',
                'show_var'        => 'showCitySearchOptions',
                'htmx_path'       => '/wp-html/v1/location-search-options/',
                'spinner_id'      => 'city-active-search-spinner',
                'update_func'     => 'updateEventFormLocation',
                'state_1_msg'     => 'Enter a US or Canadian city',
            ]); ?>
            <input type="hidden" name="event_city" x-model="citySelected" />
        </div>
        <div>
            <label class="text-14 font-bold">State</label>
            <input type="text" name="event_state" x-model="stateInput" class="w-full" autocomplete="location-disabled" />
        </div>
        <div class="flex flex-col justify-end">
            <div class="flex flex-row">
                <label class="text-14 font-bold">Postal Code</label>
                <span id="zip-active-search-spinner" class="px-2 inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                </span>
            </div>
            <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
                'container_class' => 'relative flex items-center',
                'image_class'     => 'h-4 absolute bottom-3 left-3 opacity-60',
                'image_file'      => 'location-2.svg',
                'id'              => 'edit-event-zip',
                'input_class'     => 'has-icon',
                'input_name'      => 'pc_search',
                'placeholder'     => 'Postal Code',
                'autocomplete'    => 'postal-code-disabled',
                'required'        => false,
                'input_var'       => 'zipCodeInput',
                'selected_var'    => 'zipCodeSelected',
                'show_var'        => 'showZipSearchOptions',
                'htmx_path'       => '/wp-html/v1/location-search-options-pc/',
                'spinner_id'      => 'zip-active-search-spinner',
                'update_func'     => 'updateEventFormLocation',
                'state_1_msg'     => 'Enter a US or Canada postal code (ex. 78701, A1A)',
            ]); ?>
            <input type="hidden" name="event_zip_code" x-model="zipCodeSelected" />
        </div>
    </div>
    <input type="hidden" name="event_lat" x-model="lat" />
    <input type="hidden" name="event_lng" x-model="lng" />
</div>
