<input class="has-icon"
    type="text" id="pc_search" name="pc_search"
    autocomplete="postal-code-disabled" required
    placeholder="Postal Code"
    title="Enter a US or Canada postal code (ex. 78701, A1A)."
    x-model="zipCodeInput"
    x-on:focus="showZipSearchOptions = true; zipCodeInput = '';"
    x-on:click.away="showZipSearchOptions = false; zipCodeInput = fullLocation;"
    hx-get="<?php echo site_url('/wp-html/v1/lf-location-search-options/'); ?>"
    hx-trigger="input changed delay:300ms"
    hx-target="#zip-active-search-results"
    hx-indicator="#zip-active-search-spinner"
/>
<div id="zip-active-search-results" x-show="showZipSearchOptions" x-cloak>
    <?php echo get_template_part('template-parts/search/active-search/lf-location-search-state-1', '', array()); ?>
</div>
