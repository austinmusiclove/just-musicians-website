<?php
/**
 * Template for individual event view
 *
 * @package JustMusicians
 */

// Authorize
if (!current_user_can('manage_options')) {
    $auth = user_owns_event(get_the_ID());
    if (is_wp_error($auth) || !$auth) {
        wp_safe_redirect(site_url('/my-events/'));
        exit;
    }
}

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

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
            <div class="col md:col-span-6 py-6 md:py-12"
                x-data="{
                    collectionsMap:  <?php echo clean_arr_for_doublequotes($collections_map); ?>,
                    showEditForm: false,
                    eventId:        '<?php echo get_the_ID(); ?>',
                    eventName:      '<?php echo clean_str_for_doublequotes(get_field('event_name')     ?? ''); ?>',
                    startDate:      '<?php echo clean_str_for_doublequotes(get_field('start_date')     ?? ''); ?>',
                    endDate:        '<?php echo clean_str_for_doublequotes(get_field('end_date')       ?? ''); ?>',
                    startTime:      '<?php echo clean_str_for_doublequotes(get_field('start_time')     ?? ''); ?>',
                    endTime:        '<?php echo clean_str_for_doublequotes(get_field('end_time')       ?? ''); ?>',
                    addressLine1:   '<?php echo clean_str_for_doublequotes(get_field('address_line_1') ?? ''); ?>',
                    addressLine2:   '<?php echo clean_str_for_doublequotes(get_field('address_line_2') ?? ''); ?>',
                    city:           '<?php echo clean_str_for_doublequotes(get_field('city')           ?? ''); ?>',
                    state:          '<?php echo clean_str_for_doublequotes(get_field('state')          ?? ''); ?>',
                    zipCode:        '<?php echo clean_str_for_doublequotes(get_field('zip_code')       ?? ''); ?>',
                    lat:            '<?php echo clean_str_for_doublequotes(get_field('latitude')       ?? ''); ?>',
                    lng:            '<?php echo clean_str_for_doublequotes(get_field('longitude')      ?? ''); ?>',
                    details:        '<?php echo clean_str_for_doublequotes(get_field('details')        ?? ''); ?>',
                    budget:         '<?php echo clean_str_for_doublequotes(get_field('budget')         ?? ''); ?>',
                    compensation:   '<?php echo clean_str_for_doublequotes(get_field('compensation')   ?? ''); ?>',
                    requestQuote:   '<?php echo clean_str_for_doublequotes(get_field('request_quote')  ?? ''); ?>',
                    requestDraw:    '<?php echo clean_str_for_doublequotes(get_field('request_draw')   ?? ''); ?>',
                    genres:          <?php echo clean_arr_for_doublequotes( wp_list_pluck(get_the_terms(get_the_ID(), 'genre') ?: [], 'name'),); ?>,
                    ensembleSize:    <?php echo clean_arr_for_doublequotes( sort_ensemble_size_options(wp_list_pluck(get_the_terms(get_the_ID(), 'ensemble_size') ?: [], 'name')),); ?>,
                    proposal_ids:    <?php echo clean_arr_for_doublequotes(hm_get_proposal_ids_by_event_id(get_the_ID())); ?>,
                    _updateEvent(event) { updateEvent(this, event); },
                }"
                x-on:update-event="_updateEvent($event.detail.event); showEditForm = false;"
            >

                <a href="<?php echo site_url('/my-events/'); ?>" class="inline-flex items-center gap-1 text-14 text-black/60 hover:text-black mb-8 sm:mb-16">
                    <span>←</span>
                    <span>Back to My Events</span>
                </a>

                <div class="mb-6 md:mb-14 flex justify-start items-end gap-3 flex-row">
                    <div class="w-20 shrink-0">
                        <?php echo get_template_part('template-parts/global/calendar/css-calendar-img', '', ['alpine_var' => 'startDate']); ?>
                    </div>
                    <h1 class="font-bold text-25" x-text="eventName"></h1>
                </div>

                <!------------ Page Load Toasts ----------------->
                <div>
                    <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'create') { ?><span x-init="$dispatch('success-toast', {'message': 'Event Created Successfully'});"></span><?php } ?>
                </div>

                <div x-data="{
                    showEventDetails: true,
                    showApplicants: false,
                    hideTabs() {
                        this.showEventDetails = false;
                        this.showApplicants = false;
                    },
                }">
                    <!-- Tabs -->
                    <div class="flex items-start justify-between border-b border-black/20">
                        <div class="flex gap-6 items-start">
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showEventDetails}" x-on:click="hideTabs(); showEventDetails = true;">Event Details</div>
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer relative" :class="{'active': showApplicants}" x-on:click="hideTabs(); showApplicants = true;">
                                Musicians
                                <span class="absolute top-0 left-0 -translate-x-3/4 -translate-y-1/2 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full"
                                    x-show="get_event_count_for_proposals(notifications, proposal_ids) > 0"
                                    x-text="get_event_count_for_proposals(notifications, proposal_ids)">
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4" x-show="showEventDetails" x-cloak>
                        <?php echo get_template_part('template-parts/events/event-details'); ?>
                    </div>

                    <div class="pt-4" x-show="showApplicants" x-cloak>
                        <?php echo get_template_part('template-parts/events/event-applicants'); ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
