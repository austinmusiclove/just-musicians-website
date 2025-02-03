<div data-element="mobile-search" class="hidden w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center">
    <div class="bg-white relative relative w-full h-full overflow-scroll">

    <div data-search="mobile" class="bg-yellow/20 p-2">
        <div class="w-full relative border border-black/20 rounded-sm mb-1">
            <button data-trigger="mobile-search" class="px-2 h-full absolute top-0 left-0 flex items-center justify-center">
                <img class="h-5 absolute" src="<?php echo get_template_directory_uri() . '/lib/images/icons/arrow_left.svg' ?>" />
            </button>
            <input data-input="search" class="w-full py-2 pr-3 pl-6 inline-block" type="text" placeholder="Search" />
        </div>
        <div class="w-full relative border border-black/20 rounded-sm">
            <img class="h-4 absolute top-2 left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <input class="w-full h-full py-2 pr-3 pl-6" type="text" placeholder="Austin, Texas" />
        </div>
    </div>

    <div class="p-8">
        <?php echo get_template_part('template-parts/search/mobile-search-state-1', '', array()); ?>
        <?php echo get_template_part('template-parts/search/mobile-search-state-2', '', array()); ?>
    </div>

       
    </div>
</div>