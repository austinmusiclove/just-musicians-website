<input id="inquiry-location-input"
    class="w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none"
    type="text" name="location" autocomplete="off"
    placeholder="Start typing your city or postal code.."
    :class="{ 'shake': shakeElements.has('inquiry-location-input') }"
    x-ref="inquiryLocationInputElm"
    x-model="inquiryLocationInput"
    x-on:focus="showInquiryLocationSearchOptions = true; inquiryLocationInput = '';"
    x-on:click.away="showInquiryLocationSearchOptions = false; inquiryLocationInput = inquiryLocation;"
    hx-get="<?php echo site_url('/wp-html/v1/if-location-search-options/'); ?>"
    hx-trigger="input changed delay:300ms"
    hx-target="#inquiry-location-active-search-results"
    hx-indicator=".inquiry-location-active-search-spinner"
    x-on:keydown.enter.prevent
/>
<div id="inquiry-location-active-search-results" x-show="showInquiryLocationSearchOptions" x-cloak>
    <?php echo get_template_part('template-parts/search/active-search/if-location-search-state-1', '', array()); ?>
</div>
