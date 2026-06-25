
<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
    hx-get="<?php echo site_url('/wp-html/v1/my-events/?page=' . $args['next_page']); ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-target="#results"
    hx-indicator="#events-spinner-bottom"
    hx-include="#my-events-form"
    <?php } ?>
>

    <div class="w-20 sm:w-24 shrink-0">
        <?php echo get_template_part('template-parts/global/calendar/css-calendar-img', '', ['timestamp' => $args['start_date'] ? strtotime($args['start_date']) : null ]); ?>
    </div>

    <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">
        <div class="flex flex-row items-start justify-between gap-2">
            <a href="<?php echo esc_url($args['permalink']); ?>"><h2 class="text-18 sm:text-20 font-semibold"><?php echo esc_html($args['event_name']); ?></h2></a>
        </div>

        <?php echo get_template_part('template-parts/cards/card-components/event-meta-line', '', [
            'start_date'     => $args['start_date'],
            'end_date'       => $args['end_date'],
            'start_time'     => $args['start_time'],
            'end_time'       => $args['end_time'],
            'address_line_1' => $args['address_line_1'],
            'address_line_2' => $args['address_line_2'],
            'city'           => $args['city'],
            'state'          => $args['state'],
            'zip_code'       => $args['zip_code'],
        ]); ?>

        <?php if ($args['details']) { ?>
            <div class="flex flex-col min-h-[1.5rem]">
                <span class="text-12 text-black/50 font-semibold">Details</span>
                <p class="text-14"><?php echo esc_html($args['details']); ?></p>
            </div>
        <?php } ?>

        <?php if ($args['budget'] || $args['compensation']) { ?>
            <div class="flex flex-col min-h-[1.5rem]">
                <span class="text-12 text-black/50 font-semibold">Compensation</span>
                <div class="flex flex-wrap items-center gap-2 text-14">
                    <?php if ($args['budget']) { ?>
                        <span>$<?php echo esc_html(number_format((float) $args['budget'])); ?></span>
                    <?php } ?>
                    <?php if ($args['compensation']) { ?>
                        <span><?php echo esc_html($args['compensation']); ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <!-- Manage Event -->
        <div class="flex items-center justify-between gap-2 pt-2"
            x-data="{ proposals: <?php echo clean_arr_for_doublequotes($args['proposals']); ?>, }"
        >
            <div class="flex items-center gap-1">
                <span class="text-12 text-black/50 font-semibold">Applicants</span>
                <span class="text-14"><?php echo count($args['proposals']); ?></span>
            </div>
            <a href="<?php echo esc_url($args['permalink']); ?>" class="relative bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
                Manage Event
                <span class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 bg-red text-white text-12 w-4 h-4 p-[.6rem] flex items-center justify-center rounded-full"
                    x-show="get_event_count_for_proposals(notifications, proposals) > 0" x-cloak
                    x-text="get_event_count_for_proposals(notifications, proposals)"
                ></span>
            </a>
        </div>
    </div>

</div>
