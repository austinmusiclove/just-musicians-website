<div class="bg-white font-regular font-sans text-16 flex flex-col -ml-2">

    <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/account/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
        <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['account_notification_count']" x-show="notifications['account_notification_count'] > 0" x-cloak></span>
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Account</span>
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/collections/'); ?>">
        <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Collections</span>
    </a>
    <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/messages/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/speech-bubble.svg'; ?>" />
        <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['unread_convo_count']" x-show="notifications['unread_convo_count'] > 0" x-cloak></span>
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Messages</span>
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/inquiries/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Inquiries</span>
    </a>

    <span class="px-2 py-1 mt-1 text-13 font-bold text-black/40 uppercase tracking-wider pointer-events-none select-none" x-text="showSidebar ? 'For Musicians' : 'M'"></span>
    <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/listings/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Listings</span>
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo site_url('/my-gigs/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Gigs</span>
    </a>
    <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#"> -->
    <!--     <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Applications</span> -->
    <!-- </a> -->

    <span class="px-2 py-1 mt-1 text-13 font-bold text-black/40 uppercase tracking-wider pointer-events-none select-none" x-text="showSidebar ? 'For Buyers' : 'B'"></span>
    <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#"> -->
    <!--     <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Events</span> -->
    <!-- </a> -->
    <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#"> -->
    <!--     <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Venues</span> -->
    <!-- </a> -->
    <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#"> -->
    <!--     <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>My Applications</span> -->
    <!-- </a> -->

    <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo wp_logout_url('/'); ?>">
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
        <span class="inline-block pr-6" x-show="showSidebar" x-transition x-cloak>Log Out</span>
    </a>

</div>
