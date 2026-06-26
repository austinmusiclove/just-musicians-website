<?php
/**
 * The template for the my events page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container md:grid md:grid-cols-9 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                  <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                </div>
            </div>
            <div class="col md:col-span-6 py-6 md:py-12">

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="<?php echo site_url('/my-events/'); ?>"><h1 class="font-bold text-25">My Events</h1></a>
                    <?php if (is_user_logged_in()) { ?>
                        <a href="<?php echo site_url('/event-form/'); ?>" class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer inline-block">Add +</a>
                    <?php } ?>
                </div>

                <!------------ Page Load Toasts ----------------->
                <div>
                    <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'delete') { ?><span x-init="$dispatch('success-toast', {'message': 'Event Deleted Successfully'});"></span><?php } ?>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your events';"></span>

                    <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                        <div class="pb-32 relative z-10">
                            <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your events</span>
                            <button x-on:click="showLoginModal = true;" type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                        </div>

                        <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                        <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                    </div>

                <?php } else { ?>

                    <form id="my-events-form"
                        x-data="{
                            dateRange: 'upcoming',
                        }"
                        hx-get="<?php echo site_url('/wp-html/v1/my-events/'); ?>"
                        hx-target="#results"
                        hx-indicator="#events-spinner-top"
                        hx-trigger="load, filterupdate"
                    >
                        <input type="hidden" name="date_range" x-model="dateRange" />

                        <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-black/20">

                            <div class="flex items-center gap-1">
                                <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 capitalize"
                                    :class="dateRange === 'upcoming' ? 'bg-yellow hover:bg-yellow-light' : 'hover:bg-yellow-light'"
                                    x-on:click="dateRange = 'upcoming'; $nextTick(() => $dispatch('filterupdate'));">Upcoming</button>
                                <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 capitalize"
                                    :class="dateRange === 'past' ? 'bg-yellow hover:bg-yellow-light' : 'hover:bg-yellow-light'"
                                    x-on:click="dateRange = 'past'; $nextTick(() => $dispatch('filterupdate'));">Past</button>
                            </div>

                            <div id="events-spinner-top" class="flex items-center justify-center htmx-indicator">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                            </div>

                        </div>

                    </form>

                    <span id="results"></span>

                    <div id="events-spinner-bottom" class="my-8 flex items-center justify-center htmx-indicator">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
