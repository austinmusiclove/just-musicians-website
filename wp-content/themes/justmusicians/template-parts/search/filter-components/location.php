<?php
$device = $args['device'] ?? 'desktop';
$distance_input_ref = $device . 'DistanceInput';
?>
<div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0" x-show="showLocationFilter" x-cloak>
    <div class="flex items-center gap-2 mb-3">
        <h3 class="font-bold text-18">Location</h3>
        <span id="location-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>
    <div class="flex flex-col gap-y-2">
        <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
            'container_class' => 'relative flex items-center',
            'image_class'     => 'absolute h-4 left-2',
            'image_file'      => 'location.svg',
            'id'              => 'sidebar-location-filter',
            'input_class'     => 'h-full w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none',
            'input_name'      => 'location',
            'placeholder'     => 'Start typing your city or postal code..',
            'autocomplete'    => 'location-disabled',
            'required'        => false,
            'input_var'       => 'locationInput',
            'selected_var'    => 'searchLocation',
            'show_var'        => 'showLocationSearchOptions',
            'htmx_path'       => '/wp-html/v1/location-search-options/',
            'spinner_id'      => 'location-active-search-spinner',
            'update_func'     => 'updateLocation',
            'state_1_msg'     => 'Start typing a city or postal code..',
        ]); ?>
        <input type="hidden" name="location_label" x-model="searchLocation" />
        <input type="hidden" name="lat" value="30.2672" x-model="searchLat" />
        <input type="hidden" name="lng" value="-97.7431" x-model="searchLng" />
        <select id="distance" name="distance" x-model="distance" x-ref="<?php echo $distance_input_ref; ?>" x-on:change="$dispatch('filterupdate');">
            <option value="10">10 mile radius</option>
            <option value="20">20 mile radius</option>
            <option value="40">40 mile radius</option>
            <option value="60">60 mile radius</option>
        </select>
    </div>
</div>
