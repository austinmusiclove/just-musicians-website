<?php
/**
 * The template for the my gigs page
 *
 * @package JustMusicians
 */

get_header();

$valid_statuses = ['all', 'request', 'available', 'unavailable'];
$initial_status = !empty($_GET['status']) && in_array($_GET['status'], $valid_statuses, true)
    ? $_GET['status']
    : 'all';

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
                    <a href="<?php echo site_url('/my-gigs/'); ?>"><h1 class="font-bold text-25">My Gigs</h1></a>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'see your gigs' ]); ?>

                <?php } else { ?>

                    <form id="my-gigs-form"
                        x-data="{
                            dateRange: 'upcoming',
                        }"
                        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/'); ?>"
                        hx-target="#results"
                        hx-indicator="#gig-spinner-top"
                        hx-trigger="load, filterupdate"
                    >
                        <input type="hidden" name="date_range" x-model="dateRange" />

                        <!-- Filter bar -->
                        <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-black/20">

                            <!-- Listing dropdown -->
                            <?php
                                $user_listings = get_user_listings(get_current_user_id());
                                $listing_options = [['value' => 'all', 'label' => 'All Listings', 'show' => 'true']];
                                foreach ($user_listings as $id => $name) {
                                    $listing_options[] = ['value' => (string) $id, 'label' => $name, 'show' => 'true'];
                                }
                            ?>
                            <div x-on:filter_listing-changed="$dispatch('filterupdate');">
                                <?php get_template_part('template-parts/global/form/dropdown', '', [
                                    'options'     => $listing_options,
                                    'input_name'  => 'filter_listing',
                                    'selected'    => 'all',
                                ]); ?>
                            </div>

                            <!-- Status dropdown -->
                            <div x-on:filter_status-changed="$dispatch('filterupdate');">
                                <?php get_template_part('template-parts/global/form/dropdown', '', [
                                    'options'     => [
                                        ['value' => 'all',         'label' => 'All Gigs'],
                                        ['value' => 'request',     'label' => 'Action Required'], // inquiry and stale
                                        ['value' => 'available',   'label' => 'Available'],
                                        ['value' => 'unavailable', 'label' => 'Unavailable'],
                                    ],
                                    'input_name'  => 'filter_status',
                                    'selected'    => $initial_status,
                                ]); ?>
                            </div>

                            <!-- Date range toggle -->
                            <div class="flex items-center gap-1 border-l border-black/20 pl-2 ml-1">
                                <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 capitalize"
                                    :class="dateRange === 'upcoming' ? 'bg-yellow hover:bg-yellow-light' : 'hover:bg-yellow-light'"
                                    x-on:click="dateRange = 'upcoming'; $nextTick(() => $dispatch('filterupdate'));">Upcoming</button>
                                <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 capitalize"
                                    :class="dateRange === 'past' ? 'bg-yellow hover:bg-yellow-light' : 'hover:bg-yellow-light'"
                                    x-on:click="dateRange = 'past'; $nextTick(() => $dispatch('filterupdate'));">Past</button>
                            </div>

                            <div id="gig-spinner-top" class="flex items-center justify-center htmx-indicator">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                            </div>

                        </div>

                    </form>

                    <span id="results"></span>

                    <div id="gig-spinner-bottom" class="my-8 flex items-center justify-center htmx-indicator">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
