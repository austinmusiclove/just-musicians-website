<div class="mt-28 md:mt-16 w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center" x-show="showMobileMenu" x-transition x-cloak>
    <div class="bg-white relative p-8 md:pt-20 relative w-full h-full">

    <?php $class = 'border-b border-black/20 last:border-none pb-3 mb-3'; ?>

    <div>
        <!-- Live Music -->
        <div class="<?php echo $class; ?>">

            <div class="flex justify-between items-center" x-on:click="showMobileMenuDropdown1 = ! showMobileMenuDropdown1">
                <a class="font-sun-motter" href="#">Live Music</a>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </div>
            <!-- Dropdown menu -->
            <div class="bg-white font-regular font-sans text-16 flex flex-col mt-2" x-show="showMobileMenuDropdown1" x-transition x-cloak>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Band'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                    Bands
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Solo Artist'); ?>">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                    Solo Artists
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=DJ'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                    DJs
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qtag=Wedding Music'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                    Wedding Music
                </a>
            </div>

        </div>

        <!-- Logged In Menu -->
        <div class="<?php echo $class; ?>" x-cloak x-show="loggedIn">

            <div class="flex justify-between items-center" x-on:click="showMobileMenuDropdown2 = ! showMobileMenuDropdown2">
                <a class="font-sun-motter" href="#">My Account</a>
                <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </div>
            <!-- Dropdown menu -->
            <div class="bg-white font-regular font-sans text-16 flex flex-col mt-2" x-show="showMobileMenuDropdown2" x-transition x-cloak>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="#">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
                    Account
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/listings/'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                    My Listings
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/collections/'); ?>">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
                    Collections
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/inquiries/'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
                    Inquiries
                </a>
                </a>
                <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/messages/'); ?>">
                    <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/speech-bubble.svg'; ?>" />
                    <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notificationCount" x-show="notificationCount > 0" x-cloak></span>
                    Messages
                </a>
            </div>

        </div>

        <!-- Blog -->
        <div class="<?php echo $class; ?>">
        <a class="font-sun-motter" href="<?php echo site_url('/blog/'); ?>">Blog</a>
        </div>

    </div>

    <!-- Log out -->
    <div class="<?php echo $class; ?> mt-8" x-cloak x-show="loggedIn">
        <a class="font-sun-motter" href="<?php echo wp_logout_url('/'); ?>">Log Out</a>
    </div>


    </div>
</div>
