<?php
/**
 * The template for the my events page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="flex flex-col grow relative">
        <div class="container flex grow flex-col md:grid md:grid-cols-9 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                    <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                </div>
            </div>
            <div class="col grow md:col-span-6 pt-6 md:pt-12 pb-4 flex flex-col">

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="<?php echo site_url('/my-events/'); ?>">
                        <h1 class="font-bold text-28">My Events</h1>
                    </a>
                    <?php if (is_user_logged_in()) { ?>
                        <a href="<?php echo site_url('/event-form/'); ?>" class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer inline-block">Add event +</a>
                    <?php } ?>
                </div>

                <!------------ Page Load Toasts ----------------->
                <div>
                    <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'delete') { ?><span x-init="$dispatch('success-toast', {'message': 'Event Deleted Successfully'});"></span><?php } ?>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'see your events' ]); ?>

                <?php } else { ?>

                <form id="my-events-form"
                    x-data="{
                        dateRange: 'upcoming',
                    }"
                    hx-get="<?php echo site_url('/wp-html/v1/my-events/'); ?>"
                    hx-target="#results"
                    hx-indicator=".events-spinner"
                    hx-trigger="load, filterupdate"
                >
                    <input type="hidden" name="date_range" x-model="dateRange" />

                    <div class="flex flex-wrap items-center gap-2 mb-4 border-b border-black/20">

                        <div class="flex items-center gap-4">
                            <button type="button" class="text-20 font-bold px-2 py-1 border-b-2 "
                                :class="dateRange === 'upcoming' ? 'border-black' : 'text-black/40 border-white'"
                                x-on:click="dateRange = 'upcoming'; $nextTick(() => $dispatch('filterupdate'));">Upcoming</button>
                            <button type="button" class="text-20 font-bold px-2 py-1 border-b-2 "
                                :class="dateRange === 'past' ? 'border-black' : 'text-black/40 border-white'"
                                x-on:click="dateRange = 'past'; $nextTick(() => $dispatch('filterupdate'));">Past</button>
                        </div>

                        <div id="events-spinner-top" class="items-center justify-center htmx-indicator events-spinner">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                        </div>

                    </div>

                </form>

                <span id="results" class="flex flex-col grow"></span>

                <div id="events-spinner-bottom" class="my-8 items-center justify-center htmx-indicator-flex events-spinner">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
