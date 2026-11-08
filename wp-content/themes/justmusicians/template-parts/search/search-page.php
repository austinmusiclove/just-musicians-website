<?php

$qcategory        = !empty($args['qcategory'])        ? resolve_taxonomy_name('mcategory', $args['qcategory']) : '';
$qgenre           = !empty($args['qgenre'])           ? resolve_taxonomy_name('genre', $args['qgenre'])       : '';
$qsubgenre        = !empty($args['qsubgenre'])        ? resolve_taxonomy_name('subgenre', $args['qsubgenre']) : '';
$qinstrumentation = !empty($args['qinstrumentation']) ? resolve_taxonomy_name('instrumentation', $args['qinstrumentation']) : '';
$qsetting         = !empty($args['qsetting'])         ? resolve_taxonomy_name('setting', $args['qsetting'])   : '';

$categories       = $qcategory        !== '' ? [$qcategory]        : [];
$genres           = $qgenre           !== '' ? [$qgenre]           : [];
$subgenres        = $qsubgenre        !== '' ? [$qsubgenre]        : [];
$instrumentations = $qinstrumentation !== '' ? [$qinstrumentation] : [];
$settings         = $qsetting         !== '' ? [$qsetting]         : [];

$hide_location_filter = $args['hide_location_filter'] ?? false;
$hide_category_filter = $args['hide_category_filter'] ?? false;
$hide_subgenre_filter = $args['hide_subgenre_filter'] ?? false;

$arg_location_label = $args['location_label'] ?? '';
$arg_lat            = $args['lat'] ?? null;
$arg_lng            = $args['lng'] ?? null;
$location_label     = $arg_location_label ?: (!empty($_GET['location_label']) ? $_GET['location_label'] : '');
$lat                = $arg_lat ?: (!empty($_GET['lat']) ? (float)$_GET['lat'] : null);
$lng                = $arg_lng ?: (!empty($_GET['lng']) ? (float)$_GET['lng'] : null);
$postal_codes       = !empty($args['postal_codes']) ? array_values((array) $args['postal_codes']) : [];
$priority_listings  = !empty($args['priority_listings']) ? array_values((array) $args['priority_listings']) : [];
$distance_miles     = (isset($args['distance_miles']) && filter_var($args['distance_miles'], FILTER_VALIDATE_INT) !== false) ? (int)$args['distance_miles'] : 60;

// Get listings
$result = null;
if ($args['send_first_page']) {
    $result = get_listings([
        'search'            => !empty($_GET['search']) ? $_GET['search'] : '',
        'lat'               => $lat,
        'lng'               => $lng,
        'distance_miles'    => $distance_miles,
        'categories'        => $categories,
        'genres'            => $genres,
        'subgenres'         => $subgenres,
        'instrumentations'  => $instrumentations,
        'settings'          => $settings,
        'verified'          => false,
        'postal_codes'      => $postal_codes,
        'priority_listings' => $priority_listings,
        'page'              => 1,
    ]);
}
$listings        = $result ? $result['listings']        : null;
$max_num_results = $result ? $result['max_num_results'] : null;
$max_num_pages   = $result ? $result['max_num_pages']   : null;
$is_last_page    = $result ? 1 == $max_num_pages        : null;
$next_page       = $result ? $result['next_page']       : null;

?>

<div id="page" class="flex flex-col grow">
    <form id="hx-form"
        x-data="searchPageData({
            showLocationFilter:          <?php echo $hide_location_filter ? 'false' : 'true'; ?>,
            showCategoryFilter:          <?php echo $hide_category_filter ? 'false' : 'true'; ?>,
            showSubgenreFilter:          <?php echo $hide_subgenre_filter ? 'false' : 'true'; ?>,
            listingSearchVal:           '<?php if (!empty($_GET['qsearch'])) { echo clean_str_for_doublequotes(wp_unslash($_GET['qsearch'])); } ?>',
            categoriesCheckboxes:        <?php echo clean_arr_for_doublequotes($categories); ?>,
            genresCheckboxes:            <?php echo clean_arr_for_doublequotes($genres); ?>,
            subgenresCheckboxes:         <?php echo clean_arr_for_doublequotes($subgenres); ?>,
            instrumentationsCheckboxes:  <?php echo clean_arr_for_doublequotes($instrumentations); ?>,
            settingsCheckboxes:          <?php echo clean_arr_for_doublequotes($settings); ?>,
            locationInput:              '<?php echo clean_str_for_doublequotes($location_label ?? ''); ?>',
            searchLocation:             '<?php echo clean_str_for_doublequotes($location_label ?? ''); ?>',
            searchLat:                   <?php echo $lat !== null ? $lat : 'null'; ?>,
            searchLng:                   <?php echo $lng !== null ? $lng : 'null'; ?>,
            skipCategoryFilter:          <?php echo $hide_category_filter ? 'true' : 'false'; ?>,
            distanceMiles:               <?php echo $distance_miles; ?>,
            collectionsMap:              <?php echo clean_arr_for_doublequotes($args['collections_map']); ?>,
        })"
        hx-get="<?php echo site_url('/wp-html/v1/listings/'); ?>"
        hx-target="#results"
        hx-indicator=".spinner-start"
        <?php if ($args['send_first_page']) { ?>
            hx-trigger="filterupdate"
        <?php } else { ?>
            hx-trigger="load, filterupdate"
        <?php } ?>
        x-on:location-detected-get-listings.window="updateLocation($event.detail)"
        x-on:filterupdate="window.scrollTo({ top: $el.getBoundingClientRect().top + window.scrollY - 100, behavior: 'instant' });"
    >
        <input type="hidden" name="search" x-model="listingSearchVal" />
        <?php foreach ($postal_codes as $postal_code) { ?>
            <input type="hidden" name="postal_codes[]" value="<?php echo $postal_code; ?>" />
        <?php } ?>
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 self-end bottom-0 shrink-0 w-full">
                      <div class="mb-8 min-h-16">
                          <div class="flex items-center justify-between mb-4">
                              <h2 class="font-sun-motter text-25">Filter</h2>
                              <button id="clear-form" type="button" class="underline opacity-60 hover:opacity-100 inline-block text-14" x-on:click="_clearListingForm()" >clear all</button>
                          </div>
                          <div class="text-14 opacity-60" x-text="selectedFilters"> <!--Producer | Gospel Choir | Solo/Duo | Acoustic--> </div>
                      </div>

                      <?php echo get_template_part('template-parts/search/filters', '', [
                          'device'           => 'desktop',
                          'categories'       => $categories,
                          'genres'           => $genres,
                          'subgenres'        => $subgenres,
                          'instrumentations' => $instrumentations,
                          'settings'         => $settings,
                      ]); ?>

                    </div>
                </div>


                <div class="col md:col-span-6 py-6 md:py-4">

                    <?php if (isset($args['breadcrumb_items'])) { ?><div class="text-14 text-grey"><?php get_template_part('template-parts/global/breadcrumb', '', ['items' => $args['breadcrumb_items'] ]); ?></div><?php } ?>
                    <?php if (isset($args['title'])) { ?><h1 class="font-sun-motter text-navy text-28 md:text-40 py-4"><?php echo $args['title']; ?></h1><?php } ?>
                    <?php if (isset($args['description'])) { ?><p class="text-16 md:text-18 text-brown-dark-3 mb-4 whitespace-pre-wrap"><?php echo $args['description']; ?></p><?php } ?>

                    <div class="flex items-center justify-between">
                        <?php echo get_template_part('template-parts/search/mobile-filter', '', [
                            'categories'       => $categories,
                            'genres'           => $genres,
                            'subgenres'        => $subgenres,
                            'instrumentations' => $instrumentations,
                            'settings'         => $settings,
                        ]); ?>
                        <?php echo get_template_part('template-parts/search/sort', '', [
                            'show_number'     => true,
                            'max_num_results' => $max_num_results,
                            'location_label'  => $location_label,
                        ]); ?>
                    </div>

                    <span class="spinner-start htmx-indicator-block">
                        <?php
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        ?>
                    </span>

                    <!-- Results container -->
                    <?php get_template_part('template-parts/cards/card-containers/listing-cards-container', '', [
                        'id'              => 'results',
                        'send_first_page' => $args['send_first_page'],
                        'listings'        => $listings,
                        'is_last_page'    => $is_last_page,
                    ]); ?>

                    <span class="page-indicator htmx-indicator-block">
                        <?php
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                        ?>
                    </span>


                    <div class="xl:hidden">
                        <?php
                        // Mobile form - moves to right column at lg breakpoint
                        echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', [
                            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                            'responsive' => 'xl:border-none xl:p-0'
                        ]);
                        ?>
                    </div>
                </div>

                <div class="hidden xl:block col-span-3 relative py-8">
                    <div class="sticky top-24">
                        <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', [
                            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                            'responsive' => 'xl:border-none xl:p-0'
                        ]); ?>
                    </div>
                </div>

                <!-- Modals -->
                <?php
                    $avail_categories = $hide_category_filter ? $categories : get_terms_decoded('mcategory', 'names', false, true); // Only pass the selected category to reduce alpinejs execution
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Category',
                        'labels' => $avail_categories,
                        'name' => 'categories',
                        'x-model' => 'categoriesCheckboxes',
                        'x-show' => 'showCategoryModal',
                        'has_search_bar' => true,
                    ]);
                    $avail_genres = get_terms_decoded('genre', 'names', false, true);
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Genre',
                        'labels' => $avail_genres,
                        'name' => 'genres',
                        'x-model' => 'genresCheckboxes',
                        'x-show' => 'showGenreModal',
                        'has_search_bar' => true,
                    ]);
                    $avail_subgenres = $hide_subgenre_filter ? $subgenres : get_terms_decoded('subgenre', 'names', false, true); // Only pass the selected category to reduce alpinejs execution
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Sub Genre',
                        'labels' => $avail_subgenres,
                        'name' => 'subgenres',
                        'x-model' => 'subgenresCheckboxes',
                        'x-show' => 'showSubgenreModal',
                        'has_search_bar' => true,
                    ]);
                    $avail_instrumentation = get_terms_decoded('instrumentation', 'names', false, true);
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Instrumentation',
                        'labels' => $avail_instrumentation,
                        'name' => 'instrumentations',
                        'x-model' => 'instrumentationsCheckboxes',
                        'x-show' => 'showInstrumentationModal',
                        'has_search_bar' => true,
                    ]);
                    $avail_settings = get_terms_decoded('setting', 'names', false, true);
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Setting',
                        'labels' => $avail_settings,
                        'name' => 'settings',
                        'x-model' => 'settingsCheckboxes',
                        'x-show' => 'showSettingModal',
                        'has_search_bar' => true,
                    ]);
                    $avail_ensemble_size_options = get_default_options('ensemble_size');
                    get_template_part('template-parts/search/filter-components/tag-modal', '', [
                        'title' => 'Ensemble Size',
                        'labels' => $avail_ensemble_size_options,
                        'name' => 'ensemble_size',
                        'x-model' => 'ensembleSizeCheckboxes',
                        'x-show' => 'showEnsembleSizeModal',
                        'has_search_bar' => false,
                    ]);
                ?>

            </div>
        </div>
    </form>
</div>
