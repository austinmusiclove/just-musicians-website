<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JustMuscians
 */
get_header();
?>

<div id="page" class="flex flex-col grow">
    <form id="hx-form"
        x-data="{
            showCategoryModal: false,
            showGenreModal: false,
            showSubGenreModal: false,
            showInstrumentationModal: false,
            showSettingModal: false,
            showInquiryModal: false,
            showSlide1: false,
            showSlide2: false,
            showSlide3: false,
            showSlide4: false,
            showSlide5: false,
            searchVal: searchInput,
            categoriesCheckboxes: [<?php if (!empty($_GET['qcategory'])) { echo "'" . $_GET['qcategory'] . "'"; } ?>],
            genresCheckboxes: [<?php if (!empty($_GET['qgenre'])) { echo "'" . $_GET['qgenre'] . "'"; } ?>],
            subgenresCheckboxes: [<?php if (!empty($_GET['qsubgenre'])) { echo "'" . $_GET['qsubgenre'] . "'"; } ?>],
            instrumentationsCheckboxes: [<?php if (!empty($_GET['qinstrumentation'])) { echo "'" . $_GET['qinstrumentation'] . "'"; } ?>],
            settingsCheckboxes: [<?php if (!empty($_GET['qsetting'])) { echo "'" . $_GET['qsetting'] . "'"; } ?>],
            tagsCheckboxes: [<?php if (!empty($_GET['qtag'])) { echo "'" . $_GET['qtag'] . "'"; } ?>],
            verifiedCheckbox: false,
            get selectedFilters() {
                return [...this.categoriesCheckboxes, ...this.genresCheckboxes, ...this.subgenresCheckboxes, ...this.instrumentationsCheckboxes, ...this.settingsCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.searchVal].filter(Boolean).join(' | ');
            },
            get selectedFiltersCount() {
                return [...this.categoriesCheckboxes, ...this.genresCheckboxes, ...this.subgenresCheckboxes, ...this.instrumentationsCheckboxes, ...this.settingsCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.searchVal].filter(Boolean).length;
            },
            tagModalSearchQuery: '', // must be defined here and not in the tag modal so that refs will still work in the checkboxes
            showTagModalOption(option) {
                return this.tagModalSearchQuery === '' || option.toLowerCase().includes(this.tagModalSearchQuery.toLowerCase());
            },
        }"
        hx-get="/wp-html/v1/listings/"
        hx-trigger="load, filterupdate"
        hx-target="#results"
        hx-indicator="#spinner"
    >
        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 self-end bottom-0 shrink-0 w-full">
                      <div class="mb-8 min-h-16">
                          <div class="flex items-center justify-between mb-4">
                              <h2 class="font-sun-motter text-25">Filter</h2>
                              <button id="clear-form" type="reset" class="underline opacity-40 hover:opacity-100 inline-block text-14" x-on:click="$nextTick(() => { searchInput = ''; $dispatch('filterupdate') });">clear all</button>
                          </div>
                          <div class="text-14 opacity-60" x-text="selectedFilters"> <!--Producer | Gospel Choir | Solo/Duo | Acoustic--> </div>
                      </div>

                      <?php echo get_template_part('template-parts/search/filters', '', array()); ?>

                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-4">

                    <div class="flex items-center justify-between md:justify-start">
                        <?php echo get_template_part('template-parts/search/mobile-filter', '', array()); ?>
                        <?php echo get_template_part('template-parts/search/sort', '', array(
                            'show_number' => true
                        )); ?>
                    </div>

                    <span id="results"
                        x-data='{
                            players: {},
                            playersMuted: true,
                            playersPaused: false,
                            _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
                            _pauseAllPlayers()             { pauseAllPlayers(this); },
                            _pausePlayer(playerId)         { pausePlayer(this, playerId); },
                            _playPlayer(playerId)          { playPlayer(this, playerId); },
                            _toggleMute()                  { toggleMute(this); },
                            _setupVisibilityListener()     { setupVisibilityListener(this); },
                        }'
                        x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoId);"
                        x-on:pause-all-youtube-players="_pauseAllPlayers()"
                        x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                        x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                        x-on:mute-youtube-players="_toggleMute()"
                        x-init="_setupVisibilityListener()"
                    >
                        <?php
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                        ?>
                    </span>


                    <div id="spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                    </div>


                    <div class="xl:hidden">
                        <?php
                        // Mobile form - moves to right column at lg breakpoint
                        echo get_template_part('template-parts/global/form-quote', '', array(
                            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                            'responsive' => 'xl:border-none xl:p-0'
                        ));
                        ?>
                    </div>
                </div>

                <div class="hidden xl:block col-span-3 relative py-8">
                    <div class="sticky top-24">
                        <?php echo get_template_part('template-parts/global/form-quote', '', array(
                            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                            'responsive' => 'xl:border-none xl:p-0'
                        )); ?>
                    </div>
                </div>

                <!-- Modals -->
                <?php
                    $categories = get_terms_decoded('mcategory', 'names', false, true);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'title' => 'Category',
                        'labels' => $categories,
                        'name' => 'categories',
                        'x-model' => 'categoriesCheckboxes',
                        'x-show' => 'showCategoryModal',
                        'has_search_bar' => true,
                    ]);
                    $genres = get_terms_decoded('genre', 'names', false, true);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'title' => 'Genre',
                        'labels' => $genres,
                        'name' => 'genres',
                        'x-model' => 'genresCheckboxes',
                        'x-show' => 'showGenreModal',
                        'has_search_bar' => true,
                    ]);
                    $subgenres = get_terms_decoded('subgenre', 'names', false, true);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'title' => 'Sub Genre',
                        'labels' => $subgenres,
                        'name' => 'subgenres',
                        'x-model' => 'subgenresCheckboxes',
                        'x-show' => 'showSubGenreModal',
                        'has_search_bar' => true,
                    ]);
                    $instrumentation = get_terms_decoded('instrumentation', 'names', false, true);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'title' => 'Instrumentation',
                        'labels' => $instrumentation,
                        'name' => 'instrumentations',
                        'x-model' => 'instrumentationsCheckboxes',
                        'x-show' => 'showInstrumentationModal',
                        'has_search_bar' => true,
                    ]);
                    $settings = get_terms_decoded('setting', 'names', false, true);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'title' => 'Setting',
                        'labels' => $settings,
                        'name' => 'settings',
                        'x-model' => 'settingsCheckboxes',
                        'x-show' => 'showSettingModal',
                        'has_search_bar' => true,
                    ]);
                    echo get_template_part('template-parts/global/form-quote/popup', '', []);
                ?>

            </div>
        </div>
    </form>
</div>
<?php

get_footer();
