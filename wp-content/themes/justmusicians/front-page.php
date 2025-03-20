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
    <form id="listing-form"
        x-data="{
            showGenreModal: false,
            showTypeModal: false,
            showTagModal: false,
            showInquiryModal: false,
            showSlide1: false,
            showSlide2: false,
            showSlide3: false,
            showSlide4: false,
            showSlide5: false,
            searchVal: searchInput,
            genresCheckboxes: [<?php if (!empty($_GET['qgenre'])) { echo "'" . $_GET['qgenre'] . "'"; } ?>],
            typesCheckboxes: [<?php if (!empty($_GET['qtype'])) { echo "'" . $_GET['qtype'] . "'"; } ?>],
            tagsCheckboxes: [<?php if (!empty($_GET['qtag'])) { echo "'" . $_GET['qtag'] . "'"; } ?>],
            verifiedCheckbox: false,
            get selectedFilters() {
                return [...this.genresCheckboxes, ...this.typesCheckboxes, ...this.tagsCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.searchVal].filter(Boolean).join(' | ');
            },
            get selectedFiltersCount() {
                return [...this.genresCheckboxes, ...this.typesCheckboxes, ...this.tagsCheckboxes, this.verifiedCheckbox ? 'Verified' : '', this.searchVal].filter(Boolean).length;
            },
        }"
        hx-get="wp-html/v1/listings/"
        hx-trigger="load, filterupdate"
        hx-target="#results"
    >
        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:block md:col-span-3 border-r border-black/20 pr-8">
                    <div class="sticky pt-24 pb-24">
                      <div class="mb-8 min-h-16">
                          <div class="flex items-center justify-between mb-4">
                              <h2 class="font-sun-motter text-25">Filter</h2>
                              <button id="clear-form" type="reset" class="underline opacity-40 hover:opacity-100 inline-block text-14" x-on:click="$nextTick(() => { searchInput = ''; $dispatch('filterupdate') });">clear all</button>
                          </div>
                          <div class="text-14 opacity-60" x-text="selectedFilters"> <!--Producer | Gospel Choir | Solo/Duo | Acoustic--> </div>
                      </div>

                      <?php echo get_template_part('template-parts/search/filters', '', ['device' => 'desktop']); ?>

                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-4">

                    <div class="flex items-center justify-between md:justify-start">
                        <?php echo get_template_part('template-parts/search/mobile-filter', '', array()); ?>
                        <?php echo get_template_part('template-parts/search/sort', '', array()); ?>
                    </div>

                    <script>
                        window.onYouTubeIframeAPIReady = function () { document.dispatchEvent(new Event('youtube-api-ready')); };
                    </script>

                    <span id="results">
                        <?php
                            echo get_template_part('template-parts/search/profile-skeleton');
                            echo get_template_part('template-parts/search/profile-skeleton');
                            echo get_template_part('template-parts/search/profile-skeleton');
                            echo get_template_part('template-parts/search/profile-skeleton');
                            echo get_template_part('template-parts/search/profile-skeleton');
                        ?>
                    </span>

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
                    $genres = get_terms([ 'taxonomy' => 'genre', 'fields' => 'names', 'hide_empty' => false, ]);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'labels' => $genres,
                        'name' => 'genres',
                        'x-show' => 'showGenreModal',
                    ]);
                    $types = ['Band', 'DJ', 'Musician', 'Artist', 'Sound Engineer', 'Producer'];
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'labels' => $types,
                        'name' => 'types',
                        'x-show' => 'showTypeModal',
                    ]);
                    $tags = get_terms([ 'taxonomy' => 'tag', 'fields' => 'names', 'hide_empty' => false, ]);
                    echo get_template_part('template-parts/filters/tag-modal', '', [
                        'labels' => $tags,
                        'name' => 'tags',
                        'x-show' => 'showTagModal',
                    ]);
                    echo get_template_part('template-parts/global/form-quote/popup', '', []);
                ?>

            </div>
        </div>
    </form>
</div>
<?php

get_footer();
