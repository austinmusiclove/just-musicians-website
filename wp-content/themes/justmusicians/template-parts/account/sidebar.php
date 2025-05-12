<div>
    <h2 class="font-bold text-20 mb-4">My Account</h2>
    <div class="bg-white font-regular font-sans text-16 flex flex-col">
<!--
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/user-solid.svg'; ?>" />
            Profile
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#">
            <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
            Collections
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/list-solid.svg'; ?>" />
            Listings
        </a>
-->
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo wp_logout_url('/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
            Log Out
        </a>
    </div>
</div>
