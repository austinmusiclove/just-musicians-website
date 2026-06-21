<div class="flex flex-col gap-2 mb-4" x-data="{
    cityInput: city,
    stateInput: state,
    zipCodeInput: zip_code,
    zipCodeSelected: zip_code,
    showZipSearchOptions: false,
    listingFormUpdateLocation(location) { this.zipCodeSelected = location.postal_code; this.zipCodeInput = location.postal_code; this.cityInput = location.city; this.stateInput = location.state; lat = location.lat; lng = location.lng; },
}">
    <div>
        <label class="text-14 font-bold">Address Line 1</label>
        <input type="text" name="event_address_line_1" x-bind:value="address_line_1" class="w-full" placeholder="Street address" />
    </div>
    <div>
        <label class="text-14 font-bold">Address Line 2</label>
        <input type="text" name="event_address_line_2" x-bind:value="address_line_2" class="w-full" placeholder="Apt, suite, etc." />
    </div>
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="flex flex-col justify-end">
            <div class="flex flex-row">
                <label class="text-14 font-bold">Postal Code<span class="text-red"> *</span></label>
                <span id="zip-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                </span>
            </div>
            <div class="relative">
                <img class="h-4 absolute bottom-3 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                <input class="has-icon"
                    type="text" id="pc_search" name="pc_search"
                    autocomplete="postal-code-disabled" required
                    placeholder="Postal Code"
                    title="Enter a US or Canada postal code (ex. 78701, A1A)."
                    x-model="zipCodeInput"
                    x-on:focus="showZipSearchOptions = true; zipCodeInput = '';"
                    x-on:click.away="showZipSearchOptions = false; zipCodeInput = zipCodeSelected;"
                    hx-get="<?php echo site_url('/wp-html/v1/lf-location-search-options/'); ?>"
                    hx-trigger="input changed delay:300ms"
                    hx-target="#zip-active-search-results"
                    hx-indicator="#zip-active-search-spinner"
                />
                <div id="zip-active-search-results" x-show="showZipSearchOptions" x-cloak>
                    <?php echo get_template_part('template-parts/search/active-search/lf-location-search-state-1', '', array()); ?>
                </div>
            </div>
            <input type="hidden" name="event_zip_code" x-model="zipCodeSelected" />
        </div>
        <div>
            <label class="text-14 font-bold">City</label>
            <input type="text" name="event_city" x-model="cityInput" class="w-full" />
        </div>
        <div>
            <label class="text-14 font-bold">State</label>
            <input type="text" name="event_state" x-model="stateInput" class="w-full" />
        </div>
    </div>
    <input type="hidden" name="event_lat" x-model="lat" />
    <input type="hidden" name="event_lng" x-model="lng" />
</div>
