<div class="text-16 flex items-center gap-2 py-2">
    <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>" />
    <div class="flex items-center gap-1.5 group relative">
        Sort:
        <span class="font-bold flex items-center">
            Default
            <img class="ml-1.5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
        </span>
        <div class="absolute top-full w-40 left-0 px-4 py-4 bg-white hidden group-hover:flex flex-col shadow-md rounded-sm z-10">
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                Default
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                Highest Rated
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                Most Reviewed
            </a>
        </div>
    </div>
</div>