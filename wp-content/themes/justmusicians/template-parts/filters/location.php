<div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
    <div class="flex items-center gap-2 mb-3">
        <h3 class="font-bold text-18">Location</h3>
        <span id="location-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>
    <div class="flex flex-col gap-y-2">
        <div class="relative flex items-center">
            <img class="h-4 absolute left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <input class="w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none" type="text" name="location" autocomplete="off" minlength="2"
                x-model="locationInput"
                x-on:focus="showLocationSearchOptions = true; locationInput = '';"
                x-on:click.away="showLocationSearchOptions = false; locationInput = searchLocation;"
                x-ref="sidebarLocationSearchInput"
                hx-get="<?php echo site_url('/wp-html/v1/location-search-options/'); ?>"
                hx-trigger="input changed delay:300ms"
                hx-target="#location-active-search-results-desktop"
                hx-indicator="#location-active-search-spinner"
                hx-validate="true"
                x-init="$watch('searchLocation', value => { $dispatch('filterupdate'); })"
            />
             <div id="location-active-search-results-desktop" x-show="showLocationSearchOptions" x-cloak>
                 <?php echo get_template_part('template-parts/search/location-search-state-1', '', array()); ?>
             </div>
        </div>
        <input type="hidden" name="lat" value="30.2672" x-model="searchLat" />
        <input type="hidden" name="lng" value="-97.7431" x-model="searchLng" />
        <select id="distance" name="distance" x-model="distance" x-on:change="$dispatch('filterupdate');">
            <option value="10">10 mile radius</option>
            <option value="20">20 mile radius</option>
            <option value="40">40 mile radius</option>
            <option value="60">60 mile radius</option>
        </select>
    </div>
</div>
