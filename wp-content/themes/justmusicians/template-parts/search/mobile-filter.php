<?php $classes = 'flex text-16 items-center gap-1 group relative py-0.5 rounded-full md:hidden cursor-pointer'; ?>

<button type="button" data-trigger="mobile-filter" class="gap-1.5 border border-black/20 hover:border-black px-2 font-bold <?php echo $classes; ?>" x-on:click="showMobileFilters = ! showMobileFilters">
    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/settings.svg'; ?>" x-show="selectedFiltersCount == 0"/>
    <div class="w-5 h-5 rounded-full bg-yellow/60 flex items-center justify-center font-bold text-14" x-show="selectedFiltersCount > 0" x-text="selectedFiltersCount">0</div>
    <span>Filter</span>
</button>

<div data-element="mobile-filter" class="mt-28 md:mt-16 w-screen h-screen fixed top-0 left-0 z-20 flex items-center justify-center" x-show="showMobileFilters" x-transition x-cloak>
    <div class="bg-white relative pt-0 px-8 md:pt-20 relative w-full h-full overflow-scroll pb-56">

        <div class="flex items-center justify-between mb-4 sticky top-0 bg-white py-4 w-full">
            <button type="button" data-trigger="mobile-filter" class="underline opacity-40 hover:opacity-100 inline-block text-14" x-on:click="showMobileFilters = ! showMobileFilters" >close</button>
            <h2 class="font-sun-motter text-25">Filter</h2>
            <button type="reset" class="underline opacity-40 hover:opacity-100 inline-block text-14" x-on:click="$nextTick(() => { searchInput = ''; $dispatch('filterupdate')});">clear all</button>
        </div>

        <?php echo get_template_part('template-parts/search/filters', '', [
            'device'           => 'mobile',
            'categories'       => $args['categories'],
            'genres'           => $args['genres'],
            'subgenres'        => $args['subgenres'],
            'instrumentations' => $args['instrumentations'],
            'settings'         => $args['settings'],
        ]); ?>

        <div class="bg-white py-4 px-4 fixed bottom-0 left-0 w-screen">
            <button type="button" class="bg-navy w-full shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-5 py-3" x-on:click="showMobileFilters = false" >Update</button>
        </div>

    </div>
</div>
