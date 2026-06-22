<div class="border bg-white text-14 pr-1 rounded-sm border-black/20 grow w-full flex items-stretch">
    <div class="grow relative px-1 py-1 flex bg-white" x-on:click.outside="showSearchOptions = false" >
        <input class="w-full h-full py-2 px-3" type="text" name="s" autocomplete="off" placeholder="Search"
            x-on:focus="showSearchOptions = true; showMobileMenu = false; showMobileMenuDropdown1 = false; showMobileMenuDropdown2 = false; showMobileFilters = false;"
            x-on:keyup.enter="location.href = '/?qsearch=' + encodeURIComponent($el.value) + '&amp;lat=' + searchLat + '&amp;lng=' + searchLng + '&amp;location_label=' + encodeURIComponent(searchLocation)"
            x-model="searchInput"
            hx-get="<?php echo site_url('/wp-html/v1/search-options/'); ?>"
            hx-trigger="input changed delay:300ms"
            hx-target="#active-search-results-desktop"
            hx-indicator="#desktop-header-active-search-spinner"
            hx-include="#lat-input-header-desktop, #lng-input-header-desktop"
        />
        <span id="desktop-header-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
        <div id="active-search-results-desktop" x-show="getShowDefaultSearchOptionsDesktop()" x-cloak>
            <?php echo get_template_part('template-parts/search/active-search/search-state-1', '', array()); ?>
        </div>
        <?php echo get_template_part('template-parts/search/mobile-header-search-bar', '', array()); ?>
    </div>
    <div class="hidden md:block w-px bg-black/20 my-2"></div>
    <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
        'container_class' => 'hidden md:block grow relative px-1 py-1 flex items-center',
        'image_class'     => 'absolute h-4 top-3 left-2',
        'image_file'      => 'location.svg',
        'id'              => 'desktop-header-location-filter',
        'input_class'     => 'w-full h-full py-2 pr-3 pl-5',
        'input_name'      => 'location',
        'placeholder'     => 'Start typing your city or postal code..',
        'autocomplete'    => 'off',
        'required'        => false,
        'input_var'       => 'locationInputHeader',
        'selected_var'    => 'searchLocation',
        'show_var'        => 'showLocationSearchOptionsHeader',
        'htmx_path'       => '/wp-html/v1/location-search-options/',
        'spinner_id'      => 'desktop-header-location-active-search-spinner',
        'update_func'     => 'updateLocation',
        'state_1_msg'     => 'Start typing a city or postal code..',
    ]); ?>
    <input id="lat-input-header-desktop" type="hidden" name="lat" x-model="searchLat" />
    <input id="lng-input-header-desktop" type="hidden" name="lng" x-model="searchLng" />
    <span id="desktop-header-location-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
    </span>
    <button type="button" class="flex cursor-pointer items-center px-2 py-2 hover:scale-105" x-on:click="location.href = '/?qsearch=' + encodeURIComponent(searchInput) + '&amp;lat=' + searchLat + '&amp;lng=' + searchLng + '&amp;location_label=' + encodeURIComponent(searchLocation)">
        <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
    </button>
</div>
