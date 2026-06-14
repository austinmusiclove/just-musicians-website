<?php
$device = $args['device'] ?? 'desktop';
$target_id = 'location-active-search-results-' . $device;
?>
<input
    class="w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none"
    type="text" name="location" autocomplete="location-disabled"
    placeholder="Start typing your city or postal code.."
    x-model="locationInput"
    x-on:focus="showLocationSearchOptions = true; locationInput = '';"
    x-on:click.away="showLocationSearchOptions = false; locationInput = searchLocation;"
    hx-get="<?php echo site_url('/wp-html/v1/location-search-options/'); ?>"
    hx-trigger="input changed delay:300ms"
    hx-target="#<?php echo $target_id; ?>"
    hx-indicator=".location-active-search-spinner"
/>
<div id="<?php echo $target_id; ?>" x-show="showLocationSearchOptions" x-cloak>
    <?php echo get_template_part('template-parts/search/location-search-state-1', '', array()); ?>
</div>
