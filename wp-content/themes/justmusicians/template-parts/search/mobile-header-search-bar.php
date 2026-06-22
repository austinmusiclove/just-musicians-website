<div class="w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center" x-show="getShowDefaultSearchOptionsMobile()" x-cloak>

    <div class="bg-white relative relative w-full h-full overflow-scroll">

        <div class="bg-yellow/20 p-2" x-trap.noreturn="getShowDefaultSearchOptionsMobile()">
            <div class="w-full relative border border-black/20 rounded-sm mb-1 flex bg-white">
                <button type="button" class="px-2 h-full absolute top-0 left-0 flex items-center justify-center" x-on:click="showSearchOptions = false" x-on:focus="$focus.focus($refs.mobileSearchInput)">
                    <img class="h-5 absolute" src="<?php echo get_template_directory_uri() . '/lib/images/icons/arrow_left.svg' ?>" />
                </button>
                <input class="w-full py-2 pr-3 pl-6 inline-block" type="text" name="s" autocomplete="off" placeholder="Search"
                    x-ref="mobileSearchInput"
                    x-model="searchInput"
                    x-on:keyup.enter="location.href = '/?qsearch=' + encodeURIComponent($el.value) + '&amp;lat=' + searchLat + '&amp;lng=' + searchLng + '&amp;location_label=' + encodeURIComponent(searchLocation)"
                    hx-get="<?php echo site_url('/wp-html/v1/search-options-mobile/'); ?>"
                    hx-trigger="input changed delay:300ms"
                    hx-target="#active-search-results-mobile"
                    hx-indicator="#mobile-header-active-search-spinner"
                    hx-include="#lat-input-header-mobile, #lng-input-header-mobile"
                />
                <span id="mobile-header-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                </span>
            </div>
            <div class="bg-white text-14 pr-1 rounded-sm border border-black/20 grow w-full flex items-stretch">
                <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
                    'container_class' => 'w-full relative rounded-sm',
                    'image_class'     => 'h-4 absolute top-2 left-2',
                    'image_file'      => 'location.svg',
                    'id'              => 'mobile-header-location-filter',
                    'input_class'     => 'w-full h-full py-2 pr-3 pl-5',
                    'input_name'      => 'location',
                    'placeholder'     => 'Start typing your city or postal code..',
                    'autocomplete'    => 'location-disabled',
                    'required'        => false,
                    'input_var'       => 'locationInputHeader',
                    'selected_var'    => 'searchLocation',
                    'show_var'        => 'showLocationSearchOptionsHeader',
                    'htmx_path'       => '/wp-html/v1/location-search-options/',
                    'spinner_id'      => 'mobile-header-location-active-search-spinner',
                    'update_func'     => 'updateLocation',
                    'state_1_msg'     => 'Start typing a city or postal code..',
                ]); ?>
                <span id="mobile-header-location-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                </span>
            </div>
            <input id="lat-input-header-mobile" type="hidden" name="lat" x-model="searchLat" />
            <input id="lng-input-header-mobile" type="hidden" name="lng" x-model="searchLng" />
        </div>

        <div id="active-search-results-mobile" class="p-8" x-show="getShowDefaultSearchOptionsMobile()" x-cloak>
            <?php echo get_template_part('template-parts/search/active-search/mobile-search-state-1', '', array()); ?>
        </div>

    </div>

</div>
