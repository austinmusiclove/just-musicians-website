<?php $target_id = $args['target_id'] ?? ''; ?>
<input
    class="w-full h-full py-2 pr-3 pl-5"
    type="text" name="location" autocomplete="location-disabled"
    placeholder="Start typing your city or postal code.."
    x-model="locationInputHeader"
    x-on:focus="showLocationSearchOptionsHeader = true; locationInputHeader = '';"
    x-on:click.away="showLocationSearchOptionsHeader = false; locationInputHeader = searchLocation;"
    hx-get="<?php echo site_url('/wp-html/v1/location-search-options/'); ?>"
    hx-trigger="input changed delay:300ms"
    hx-target="#<?php echo $target_id; ?>"
/>
<div id="<?php echo $target_id; ?>" x-show="showLocationSearchOptionsHeader" x-cloak>
    <?php echo get_template_part('template-parts/search/location-search-state-1', '', array()); ?>
</div>
