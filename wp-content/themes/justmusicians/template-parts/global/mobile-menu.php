<div data-element="mobile-menu" class="mt-28 md:mt-16 w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center" x-show="showMobileMenu" x-transition x-cloak>
    <div class="bg-white relative p-8 md:pt-20 relative w-full h-full">

    <?php $class = 'border-b border-black/20 last:border-none pb-3 mb-3'; ?>

    <div class="<?php echo $class; ?>">

        <div data-trigger="mobile-menu-dropdown" class="flex justify-between items-center" x-on:click="showMobileMenuDropdown = ! showMobileMenuDropdown">
            <a class="font-sun-motter" href="#">Live Music</a>
            <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
        </div>
        <!-- Dropdown menu -->
        <div data-element="mobile-menu-dropdown" class="bg-white font-regular font-sans text-16 flex flex-col mt-2" x-show="showMobileMenuDropdown" x-transition x-cloak>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="/?qcategory=Band">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                Bands
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="/?qcategory=Solo Artist">
                <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                Solo Artists
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="/?qcategory=DJ">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                DJs
            </a>
            <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="/?qtag=Wedding Music">
                <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                Wedding Music
            </a>
        </div>

    </div>
    <div class="<?php echo $class; ?>">
        <a class="font-sun-motter" href="/blog">Blog</a>
    </div>


    </div>
</div>
