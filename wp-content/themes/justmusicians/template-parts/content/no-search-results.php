<div class="font-sun-motter text-center px-4 pb-28 pt-12 sm:py-20 relative mb-4 xl:mb-0 h-[70vh] flex items-center justify-center flex-col">

    <div class="pb-16 relative z-10">
        <span class="text-22 block text-center mb-2">Looks like this ol' trail's <span class="text-yellow">run dry!</span></span>
        <p class="text-20 mb-4">No results, but try again and you might strike gold!</p>

        <button type="reset" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
            x-on:click="$nextTick(() => { searchInput = ''; $dispatch('filterupdate') });">
            Clear Search
        </button>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
