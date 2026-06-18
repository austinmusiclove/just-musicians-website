<!-- Logged in Menu -->
<span class="relative font-sun-motter text-18 items-center gap-2 group hidden lg:flex" x-cloak x-show="loggedIn">
    <a href="#">My Account</a>
    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
    <span class="absolute top-0 left-0 -translate-x-3/4 -translate-y-1/2 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['total_notification_count']" x-show="notifications['total_notification_count'] > 0" x-cloak></span>
    <!-- Dropdown menu -->
    <div class="absolute top-full w-56 left-0 px-4 py-4 bg-white hidden font-regular font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10">
        <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/account/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
            <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['account_notification_count']" x-show="notifications['account_notification_count'] > 0" x-cloak></span>
            Account
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/collections/'); ?>">
            <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
            Collections
        </a>
        <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/messages/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/speech-bubble.svg'; ?>" />
            <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['unread_convo_count']" x-show="notifications['unread_convo_count'] > 0" x-cloak></span>
            Messages
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/inquiries/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
            Inquiries
        </a>

        <span class="px-2 py-1 mt-1 text-13 font-bold text-black/40 uppercase tracking-wider pointer-events-none select-none">For Musicians</span>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/listings/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
            My Listings
        </a>
        <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/my-gigs/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
            <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['gig_notification_count']" x-show="notifications['gig_notification_count'] > 0" x-cloak></span>
            My Gigs
        </a>
        <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="#">My Applications</a> -->

        <span class="px-2 py-1 mt-1 text-13 font-bold text-black/40 uppercase tracking-wider pointer-events-none select-none">For Buyers</span>
        <a class="relative px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo site_url('/my-events/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
            <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full" x-text="notifications['event_notification_count']" x-show="notifications['event_notification_count'] > 0" x-cloak></span>
            My Events
        </a>
        <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="#">My Venues</a> -->
        <!-- <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="#">My Applications</a> -->

        <hr class="my-2 border-black/10" />
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50" href="<?php echo wp_logout_url('/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
            Log Out
        </a>
    </div>
</span>
