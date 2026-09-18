<?php

// Autofill the location input with the same values the header search uses:
// URL query args take priority, IP geolocation (location-detected event) fills in when available.
$lat            = !empty($_GET['lat']) ? (float) $_GET['lat'] : null;
$lng            = !empty($_GET['lng']) ? (float) $_GET['lng'] : null;
$location_label = !empty($_GET['location_label']) ? $_GET['location_label'] : '';

?>

<!-- Hero Section - Search Live Musicians -->
<section
    class="bg-brown-light-3 pt-12 md:pt-24 pb-16 md:pb-28 relative overflow-hidden"
    x-data="{
        heroKeyword:       '<?php echo clean_str_for_doublequotes(wp_unslash($_GET['qsearch'])); ?>',
        heroLocationInput: '<?php echo clean_str_for_doublequotes($location_label); ?>',
        heroLocation:      '<?php echo clean_str_for_doublequotes($location_label); ?>',
        heroLat:            <?php echo $lat !== null ? $lat : 'null'; ?>,
        heroLng:            <?php echo $lng !== null ? $lng : 'null'; ?>,
        showSearchOptions: false,
        showHeroLocationSearchOptions: false,
        updateHeroLocation(location) {
            this.heroLocationInput = location.label;
            this.heroLocation = location.label;
            this.heroLat = location.lat;
            this.heroLng = location.lng;
        },
        searchHero() {
            let url = '<?php echo site_url('/live-music/search/'); ?>' + '?qsearch=' + encodeURIComponent(this.heroKeyword);
            if (this.heroLat && this.heroLng) {
                url += '&lat=' + this.heroLat + '&lng=' + this.heroLng + '&location_label=' + encodeURIComponent(this.heroLocation);
            }
            window.location.href = url;
        }
    }"
    x-on:location-detected.window="updateHeroLocation($event.detail)"
>
    <?php // URL query args take priority; IP geolocation fills in when no location was provided ?>
    <?php if ($lat === null || $lng === null) { ?>
        <div
            hx-get="<?php echo site_url('/wp-html/v1/location/'); ?>"
            hx-trigger="load"
            hx-swap="innerHTML"
        ></div>
    <?php } ?>

    <div class="container relative">
        <div class="max-w-3xl mx-auto">
            <h1 class="font-sun-motter text-navy text-28 text-center md:text-40 mb-4">Find Live Musicians Near You</h1>
            <p class="text-16 text-center md:text-20 text-brown-dark-3 mb-8 md:mb-12">
                Search for live musicians to hire near you. Filter by genre, ensemble size, instrumentation, categories, and more. Start by choosing your city or postal code.
            </p>

            <div class="flex flex-col md:flex-row gap-3 items-stretch">
                <div class="border bg-white text-14 rounded-sm border-black/20 grow w-full flex items-stretch flex-col sm:flex-row">
                    <div class="grow relative px-1 py-1 flex bg-white" x-on:click.outside="showSearchOptions = false">
                        <input
                            id="hero-keyword-search"
                            class="w-full h-full py-2 px-3"
                            type="text"
                            name="s"
                            autocomplete="off"
                            placeholder="Search for a band, artist, or genre"
                            x-model="heroKeyword"
                            x-on:focus="showSearchOptions = true"
                            x-on:keyup.enter="searchHero()"
                            hx-get="<?php echo site_url('/wp-html/v1/search-options/'); ?>"
                            hx-trigger="input changed delay:300ms"
                            hx-target="#hero-keyword-active-search-results"
                            hx-indicator="#hero-keyword-active-search-spinner"
                            hx-include="#lat-input-hero, #lng-input-hero"
                        />
                        <span id="hero-keyword-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                        </span>
                        <div id="hero-keyword-active-search-results" x-show="showSearchOptions" x-cloak>
                            <?php echo get_template_part('template-parts/search/active-search/search-state-1', '', array()); ?>
                        </div>
                    </div>
                    <div class="hidden sm:block w-px bg-black/20 my-2"></div>
                    <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
                        'container_class' => 'w-full sm:w-auto sm:grow relative px-1 py-1 flex items-center',
                        'image_class'     => 'absolute h-4 left-2',
                        'image_file'      => 'location.svg',
                        'id'              => 'hero-location-filter',
                        'input_class'     => 'w-full h-full py-2 pr-3 pl-5',
                        'input_name'      => 'location',
                        'placeholder'     => 'Start typing your city or postal code..',
                        'autocomplete'    => 'off',
                        'required'        => false,
                        'input_var'       => 'heroLocationInput',
                        'selected_var'    => 'heroLocation',
                        'show_var'        => 'showHeroLocationSearchOptions',
                        'htmx_path'       => '/wp-html/v1/location-search-options/',
                        'spinner_id'      => 'hero-location-active-search-spinner',
                        'update_func'     => 'updateHeroLocation',
                        'state_1_msg'     => 'Start typing a city or postal code..',
                    ]); ?>
                    <input id="lat-input-hero" type="hidden" name="lat" :value="heroLat" />
                    <input id="lng-input-hero" type="hidden" name="lng" :value="heroLng" />
                    <span id="hero-location-active-search-spinner" class="p-2 inset-0 flex items-center justify-center htmx-indicator">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                    </span>
                </div>

                <button type="button" class="bg-navy border-2 border-black text-white shadow-black-offset hover:bg-yellow hover:text-black font-sun-motter text-16 px-6 md:px-8 py-3 flex items-center justify-center gap-2"
                    x-on:click="searchHero()"
                >
                    <span>Search</span>
                </button>
            </div>
        </div>
    </div>
</section>
