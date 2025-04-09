<div class="text-16 py-2 flex items-center gap-2">

    <!-- Sort -->
    <div class="flex items-center gap-2 ">
        <div class="flex items-center gap-1.5 group relative h-50">
            <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-64 -top-[65px] -right-28 md:right-auto">
            Learn more about the default Just Musicians search algorithm <a class="text-yellow underline" href="<?php echo site_url('/search-algorithm'); ?>">here</a>.
            </div>
            <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
        </div>
        <div class="flex items-center gap-1.5 group relative">
            Sort:
            <span class="font-bold flex items-center">
                Default
                <img class="ml-1.5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </span>
            <div class="absolute top-full w-40 px-4 py-4 bg-white hidden group-hover:flex flex-col shadow-md rounded-sm z-10 right-0">
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Default
                </a>
                <!-- Unsupported search options
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Highest Rated
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Most Reviewed
                </a>
                -->
            </div>
        </div>
    </div>

    <!-- Number of Results -->

    <div class="flex items-center gap-2">
        <div class="h-5 w-px bg-black/20"></div>
        <span>42 results</span>
    </div>


</div>
