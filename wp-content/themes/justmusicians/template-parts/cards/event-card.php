themes/justmusicians/template-parts/cards/event-card.php<?php
$event_id     = $args['event_id'];
$event_name   = $args['event_name'];
$permalink    = $args['permalink'];
$start_date   = $args['start_date'] ?? '';
$start_time   = $args['start_time'] ?? '';
$end_time     = $args['end_time'] ?? '';
$city         = $args['city'] ?? '';
$state        = $args['state'] ?? '';
$details      = $args['details'] ?? '';
$budget       = $args['budget'] ?? '';
$compensation = $args['compensation'] ?? '';
$proposals    = $args['proposals'] ?? [];

$start_ts     = $start_date ? strtotime($start_date) : null;
$start_display = $start_ts ? gmdate('M j, Y', $start_ts) : '';

$time_display = '';
if ($start_time) {
    $time_display = gmdate('g:i A', strtotime($start_time));
    if ($end_time) {
        $time_display .= ' – ' . gmdate('g:i A', strtotime($end_time));
    }
}

$location_parts = array_filter([$city, $state]);
$location       = !empty($location_parts) ? implode(', ', $location_parts) : '';

$meta_parts = array_filter([$start_display, $time_display, $location]);
$meta_line  = !empty($meta_parts) ? implode(' • ', $meta_parts) : '';

?>

<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
    hx-get="<?php echo site_url('/wp-html/v1/my-events/?page=' . $args['next_page']); ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-target="#results"
    hx-indicator="#spinner"
    hx-include="#my-events-form"
    <?php } ?>
>

    <div class="w-20 sm:w-24 shrink-0">
        <?php echo get_template_part('template-parts/global/calendar/css-calendar-img', '', ['timestamp' => $start_ts]); ?>
    </div>

    <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">
        <div class="flex flex-row items-start justify-between gap-2">
            <a href="<?php echo esc_url($permalink); ?>"><h2 class="text-18 sm:text-20 font-semibold"><?php echo esc_html($event_name); ?></h2></a>
        </div>

        <?php if ($meta_line) { ?>
            <div class="flex items-center gap-1 min-h-[1.5rem]">
                <p class="text-14 truncate text-black/70"><?php echo esc_html($meta_line); ?></p>
            </div>
        <?php } ?>

        <?php if ($details) { ?>
            <div class="flex flex-col min-h-[1.5rem]">
                <span class="text-12 text-black/50 font-semibold">Details</span>
                <p class="text-14"><?php echo esc_html($details); ?></p>
            </div>
        <?php } ?>

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

        <!-- Manage Event -->
        <div class="flex items-center justify-between gap-2 pt-2"
            x-data="{ proposals: <?php echo clean_arr_for_doublequotes($proposals); ?>, }"
        >
            <div class="flex items-center gap-1">
                <span class="text-12 text-black/50 font-semibold">Applicants</span>
                <span class="text-14"><?php echo count($proposals); ?></span>
            </div>
            <a href="<?php echo esc_url($permalink); ?>" class="relative bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
                Manage Event
                <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full"
                    x-show="(notifications?.inquiry_response_proposal_ids ?? []).filter(id => proposals.includes(id)).length > 0" x-cloak
                    x-text="(notifications?.inquiry_response_proposal_ids ?? []).filter(id => proposals.includes(id)).length"
                ></span>
            </a>
        </div>
    </div>

</div>
