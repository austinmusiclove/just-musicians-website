<div data-element="mobile-search" class="w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center" x-show="getShowDefaultSearchOptionsMobile()" x-cloak>
    <div class="bg-white relative relative w-full h-full overflow-scroll">

    <div data-search="mobile" class="bg-yellow/20 p-2" x-trap.noreturn="getShowDefaultSearchOptionsMobile()">
        <div class="w-full relative border border-black/20 rounded-sm mb-1">
            <button type="button" data-trigger="mobile-search" class="px-2 h-full absolute top-0 left-0 flex items-center justify-center" x-on:click="showSearchOptions = false" x-on:focus="$focus.focus($refs.mobileSearchInput)">
                <img class="h-5 absolute" src="<?php echo get_template_directory_uri() . '/lib/images/icons/arrow_left.svg' ?>" />
            </button>
            <input data-input="search" class="w-full py-2 pr-3 pl-6 inline-block" type="text" name="s" autocomplete="off" placeholder="Search"
              x-on:focus="$dispatch('updatesearchoptions')"
              x-ref="mobileSearchInput"
              x-bind:value="searchInput"
              x-on:keyup.enter="searchInput = $el.value"
              hx-get="<?php echo site_url('/wp-html/v1/search-options-mobile'); ?>"
              hx-trigger="input changed delay:300ms, updatesearchoptions"
              hx-target="#active-search-results-mobile"
            />
        </div>
        <div class="w-full relative border border-black/20 rounded-sm">
            <img class="h-4 absolute top-2 left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <input class="w-full h-full py-2 pr-3 pl-6" type="text" placeholder="Austin, Texas" disabled />
        </div>
    </div>

    <div id="active-search-results-mobile" class="p-8" x-show="getShowDefaultSearchOptionsMobile()" x-cloak>
        <?php echo get_template_part('template-parts/search/mobile-search-state-1', '', array()); ?>
    </div>


    </div>
</div>
