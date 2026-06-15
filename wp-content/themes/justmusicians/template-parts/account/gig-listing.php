<?php

$proposal     = $args['proposal'];
$listing_name = $proposal['listing_name'];

$event        = $args['proposal']['event'];
$event_id     = $event['event_id'];
$event_name   = $event['event_name'];
$start_date   = $event['start_date'];
$end_date     = $event['end_date'];
$start_time   = $event['start_time'];
$end_time     = $event['end_time'];
$details      = $event['details'];
$budget       = $event['budget'];
$compensation = $event['compensation'];
$permalink    = $event['permalink'];

$start_ts    = $start_date ? strtotime($start_date) : null;
$end_ts      = $end_date   ? strtotime($end_date)   : null;
$start_display = $start_ts ? gmdate('F j, Y', $start_ts) : '';
$end_display   = $end_ts   ? gmdate('F j, Y', $end_ts)   : '';

$time_display = '';
if ($start_time) {
    $time_display = gmdate('g:i A', strtotime($start_time));
    if ($end_time) {
        $time_display .= ' – ' . gmdate('g:i A', strtotime($end_time));
    }
}

$location_parts = array_filter([$event['city'], $event['state']]);
$location       = !empty($location_parts) ? implode(', ', $location_parts) : '';

$details_excerpt = '';
if ($details) {
    $details_excerpt = mb_strlen($details) > 120 ? mb_substr($details, 0, 120) . '…' : $details;
}
?>

<div
    class="bg-white border border-black/20 rounded-sm p-4 flex flex-col gap-3"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-target="#results"
        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
    <?php } ?>>

    <div class="flex items-start justify-between">
        <div class="flex items-center gap-3 w-full">
            <?php echo get_template_part('template-parts/global/calendar-icon', '', ['timestamp' => $start_ts]); ?>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-2">
                    <a href="<?php echo esc_url($permalink); ?>" class="font-bold text-16 hover:underline leading-tight truncate"><?php echo esc_html($event_name); ?></a>
                    <span class="text-11 px-2 py-0.5 rounded-full bg-yellow/40 text-12 capitalize shrink-0 whitespace-nowrap font-semibold"><?php echo esc_html($proposal['status']); ?></span>
                </div>
                <?php if ($listing_name) { ?>
                    <span class="text-13 text-black/50 block mt-0.5">via <?php echo esc_html($listing_name); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-1.5 text-14 text-black/70">
        <?php if ($start_display) { ?>
            <div class="flex items-center gap-2">
                <img class="w-3.5 h-3.5 shrink-0 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
                <span><?php echo esc_html($start_display); if ($end_display && $end_display !== $start_display) { echo ' – ' . esc_html($end_display); } ?></span>
            </div>
        <?php } ?>
        <?php if ($time_display) { ?>
            <div class="flex items-center gap-2">
                <img class="w-3.5 h-3.5 shrink-0 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                <span><?php echo esc_html($time_display); ?></span>
            </div>
        <?php } ?>
        <?php if ($location) { ?>
            <div class="flex items-center gap-2">
                <img class="w-3.5 h-3.5 shrink-0 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                <span><?php echo esc_html($location); ?></span>
            </div>
        <?php } ?>
    </div>

    <?php if ($details_excerpt) { ?>
        <div class="text-14 text-black/60 border-t border-black/10 pt-3 leading-relaxed"><?php echo esc_html($details_excerpt); ?></div>
    <?php } ?>

    <?php if ($budget || $compensation) { ?>
        <div class="flex flex-wrap gap-3 border-t border-black/10 pt-3">
            <?php if ($budget) { ?>
                <div class="flex items-center gap-1.5 text-14 bg-green-50 rounded-sm px-2 py-1">
                    <img class="w-3.5 h-3.5 shrink-0 opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/dollar.svg'; ?>" />
                    <span class="font-semibold">Budget: $<?php echo esc_html(number_format((float) $budget)); ?></span>
                </div>
            <?php } ?>
            <?php if ($compensation) { ?>
                <div class="text-14 text-black/60 px-2 py-1">
                    <span class="font-semibold">Compensation:</span> <?php echo esc_html($compensation); ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="border-t border-black/10 pt-3">
        <a href="<?php echo esc_url($permalink); ?>"
            class="inline-block bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-4 py-2 hover:bg-navy hover:text-white transition-colors">
            Respond to Gig
        </a>
    </div>

</div>
