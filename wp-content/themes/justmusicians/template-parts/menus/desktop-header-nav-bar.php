<div class="font-sun-motter text-18 items-center gap-6 hidden lg:flex shrink-0">
    <span class="flex items-center gap-2 relative group">
        <a href="#">Live Music</a>
        <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
        <!-- Dropdown menu -->
        <div class="absolute top-full w-48 left-0 px-4 py-4 bg-white hidden font-regular font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10">
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Band'); ?>">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                Bands
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Solo Artist'); ?>">
                <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                Solo Artists
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=DJ'); ?>">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                DJs
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qsetting=Wedding'); ?>">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                Wedding Music
            </a>
        </div>
    </span>
    <a href="/blog/">Blog</a>
</div>
