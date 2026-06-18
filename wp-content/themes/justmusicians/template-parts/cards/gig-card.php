<?php

$proposal     = $args['proposal'];
$listing_name = $proposal['listing_name'];
$status       = $proposal['status'];
$availability = $proposal['availability'];
$prop_details = $proposal['details'];
$quote        = $proposal['quote'];
$draw         = $proposal['draw'];

$event         = $args['proposal']['event'];
$event_id      = $event['event_id'];
$event_name    = $event['event_name'];
$start_date    = $event['start_date'];
$end_date      = $event['end_date'];
$start_time    = $event['start_time'];
$end_time      = $event['end_time'];
$city          = $event['city'] ?? '';
$state         = $event['state'] ?? '';
$event_details = $event['details'];
$budget        = $event['budget'];
$compensation  = $event['compensation'];
$request_quote = $event['request_quote'];
$request_draw  = $event['request_draw'];

$start_ts    = $start_date ? strtotime($start_date) : null;
$end_ts      = $end_date   ? strtotime($end_date)   : null;
$start_display = $start_ts ? gmdate('M j, Y', $start_ts) : '';
$end_display   = $end_ts   ? gmdate('M j, Y', $end_ts)   : '';

$time_display = '';
if ($start_time) {
    $time_display = gmdate('g:i A', strtotime($start_time));
    if ($end_time) {
        $time_display .= ' – ' . gmdate('g:i A', strtotime($end_time));
    }
}

$location_parts = array_filter([$city, $state]);
$location       = !empty($location_parts) ? implode(', ', $location_parts) : '';

$date_display = $start_display;
if ($end_display && $end_display !== $start_display) {
    $date_display .= ' – ' . $end_display;
}
$meta_parts = array_filter([$date_display, $time_display, $location]);
$meta_line  = !empty($meta_parts) ? implode(' • ', $meta_parts) : '';

?>

<div class="flex flex-col mb-4"
    hx-post="<?php echo site_url('/wp-html/v1/clear-notification/'); ?>"
    x-bind:hx-trigger="(!notifications?.new_inquiry_proposal_ids?.includes(<?php echo (int) $proposal['proposal_id']; ?>)) ? 'never-trigger' : 'revealed once'"
    hx-vals='{"notification_type":"new-inquiry","subject_id":<?php echo (int) $proposal['proposal_id']; ?>}'
    hx-swap="beforeend"
>

    <div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
        <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#results"
        <?php } ?>
        x-data="{
            showForm: false,
            prop_details: '<?php echo clean_str_for_doublequotes($prop_details); ?>',
            availability: '<?php echo clean_str_for_doublequotes($availability); ?>',
            quote:        '<?php echo clean_str_for_doublequotes($quote); ?>',
            draw:         '<?php echo clean_str_for_doublequotes($draw); ?>',
            status:       '<?php echo clean_str_for_doublequotes($status); ?>',
            _updateProposal(details, availability, quote, draw) { this.showForm = false; this.prop_details = details; this.availability = availability; this.quote = quote; this.draw = draw; this.status = 'applied'},
        }"
        x-on:update-proposal="_updateProposal($event.detail.details, $event.detail.availability, $event.detail.quote, $event.detail.draw);"
    >

        <!-- Calendar Icon -->
        <div class="w-20 sm:w-24 shrink-0">
            <?php echo get_template_part('template-parts/global/calendar/css-calendar-img', '', ['timestamp' => $start_ts]); ?>
        </div>

        <!-- Info -->
        <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">

            <!-- Title + Status -->
            <div class="flex flex-row items-start justify-between gap-2">
                <h2 class="text-18 sm:text-20 font-semibold cursor-pointer"><?php echo esc_html($event_name); ?></h2>
                <span class="text-11 px-2 py-0.5 rounded-full text-12 capitalize font-semibold break-words shrink-0" :class="status == 'inquiry' ? 'bg-red/40' : 'bg-yellow/40'" x-text="status"></span>
            </div>

            <?php if ($listing_name) { ?>
                <div class="flex items-center gap-1 flex-wrap">
                    <p class="text-14 text-black/50">Listing: <?php echo esc_html($listing_name); ?></p>
                </div>
            <?php } ?>

            <!-- Meta line -->
            <?php if ($meta_line) { ?>
                <div class="flex items-center gap-1 min-h-[1.5rem]">
                    <p class="text-14 truncate text-black/70"><?php echo esc_html($meta_line); ?></p>
                </div>
            <?php } ?>

            <!-- Details -->
            <?php if ($event_details) { ?>
                <div class="flex flex-col min-h-[1.5rem]">
                    <span class="text-12 text-black/50 font-semibold">Details</span>
                    <p class="text-14"><?php echo esc_html($event_details); ?></p>
                </div>
            <?php } ?>

            <!-- Budget + Compensation -->
            <?php if ($budget || $compensation) { ?>
                <div class="flex flex-col min-h-[1.5rem]">
                    <span class="text-12 text-black/50 font-semibold">Compensation</span>
                    <div class="flex flex-wrap items-center gap-2 text-14">
                        <?php if ($budget) { ?>
                            <span>$<?php echo esc_html(number_format((float) $budget)); ?></span>
                        <?php } ?>
                        <?php if ($budget && $compensation) { ?>
                            <span class="text-black/30">|</span>
                        <?php } ?>
                        <?php if ($compensation) { ?>
                            <span><?php echo esc_html($compensation); ?></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <!-- Response -->
            <div class="flex flex-col mb-4" x-show="!showForm && status == 'applied'" x-cloak>
                <span class="text-12 text-black/50 font-semibold">Your Response</span>
                <div class="flex flex-wrap items-center gap-2">
                    <p class="w-full text-14 mb-1" x-text="prop_details" x-show="prop_details" x-cloak></p>
                    <span class="text-12 px-2 py-0.5 rounded-full bg-navy text-white capitalize font-semibold" x-text="availability" x-show="availability" x-cloak></span>
                    <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Quote: $${quote}`" x-show="quote" x-cloak></span>
                    <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Draw: ${draw}`" x-show="draw" x-cloak></span>
                </div>
            </div>

            <!-- Respond (desktop) -->
            <div class="hidden sm:flex justify-start">
                <?php get_template_part('template-parts/cards/card-components/gig-card-form', '', [
                    'proposal_id'   => $proposal['proposal_id'],
                    'request_quote' => $request_quote,
                    'request_draw'  => $request_draw,
                    'device'        => 'desktop',
                    'status'        => $status,
                ]); ?>
            </div>

            <!-- Respond (mobile) -->
            <div class="block sm:hidden w-full">
                <?php get_template_part('template-parts/cards/card-components/gig-card-form', '', [
                    'proposal_id'   => $proposal['proposal_id'],
                    'request_quote' => $request_quote,
                    'request_draw'  => $request_draw,
                    'device'        => 'mobile',
                    'status'        => $status,
                ]); ?>
            </div>

        </div>

    </div>

</div>
