<div class="container flex flex-row md:grid grid-cols-12 gap-2 md:gap-4 lg:gap-12 md:px-6 my-2">
    <!-- Logo -->
    <div class="w-32 md:w-auto col-span-2 relative">
        <a class="w-full absolute top-0 left-0 z-10" href="<?php echo get_home_url(); ?>">
            <img src="<?php echo get_template_directory_uri() . '/lib/images/logos/hm-logo-emblem-white-1.svg'; ?>" />
        </a>
    </div>

    <div class="col-span-10 flex flex-col-reverse max-md:grow md:flex-row md:items-center items-end gap-2 md:gap-6 lg:gap-12 justify-between">
        <?php echo get_template_part('template-parts/search/desktop-header-search-bar', '', []); ?>
        <?php echo get_template_part('template-parts/menus/desktop-header-nav-bar', '', []); ?>

        <div class="flex items-center gap-2 shrink-0">
            <div class="flex items-center">
                <div class="hamburger block lg:hidden h-8 w-8 cursor-pointer relative" x-on:click="showMobileMenu = ! showMobileMenu; showMobileFilters = false;" x-bind:class="{ 'active': showMobileMenu }" >
                    <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 -translate-y-2.5 transform transition duration-500 ease-in-out"></div>
                    <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 transform transition duration-500 ease-in-out"></div>
                    <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 translate-y-2.5 transform transition duration-500 ease-in-out"></div>
                </div>
            </div>
            <button class="border-2 font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3 ml-4" x-cloak x-show="!loggedIn" x-on:click="showLoginModal = !showLoginModal">Log In</button>
            <button class="bg-navy border-2 border-black text-white shadow-black-offset hover:bg-yellow hover:text-black font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3" x-cloak x-show="!loggedIn" x-on:click="showSignupModal = !showSignupModal">Sign Up</button>

            <?php echo get_template_part('template-parts/menus/desktop-header-nav-dropdown', '', []); ?>
        </div>
    </div>
</div>
