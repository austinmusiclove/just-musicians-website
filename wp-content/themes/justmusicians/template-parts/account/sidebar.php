<div x-data="{ showSidebar: <?php if ($args['collapsible']) { echo 'false'; } else { echo 'true'; } ?> }">
    <div class="flex items-center gap-8 mb-4">
        <h2 class="font-bold text-20" x-show="showSidebar" x-transition x-cloak>My Account</h2>
        <?php if ($args['collapsible']) { ?>
            <button class="w-4 flex items-center justify-center" x-on:click="showSidebar = !showSidebar">
                <img class="rotate-90 h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </button>
        <?php } ?>
    </div>

    <div class="bg-white font-regular font-sans text-16 flex flex-col -ml-2">

        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/account/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Account</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/listings/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Listings</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/collections/'); ?>">
            <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Collections</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/inquiries/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Inquiries</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/messages/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/speech-bubble.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Messages</span>
        </a>

        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo wp_logout_url('/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
            <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Log Out</span>
        </a>


    </div>
</div>
