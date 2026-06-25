<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#results"
        hx-indicator="#gig-spinner-bottom"
        hx-include="#my-gigs-form"
    <?php } ?>
    x-data="{
        showForm: false,
        prop_details:     '<?php echo clean_str_for_doublequotes($args['proposal']['details']          ?? ''); ?>',
        availability:     '<?php echo clean_str_for_doublequotes($args['proposal']['availability']     ?? ''); ?>',
        quote:            '<?php echo clean_str_for_doublequotes($args['proposal']['quote']            ?? ''); ?>',
        draw:             '<?php echo clean_str_for_doublequotes($args['proposal']['draw']             ?? ''); ?>',
        status:           '<?php echo clean_str_for_doublequotes($args['proposal']['status']           ?? ''); ?>',
        proposal_updated: '<?php echo clean_str_for_doublequotes($args['proposal']['proposal_updated'] ?? ''); ?>',
        _updateProposal(status, details, availability, quote, draw, proposal_updated) { this.showForm = false; this.prop_details = details; this.availability = availability; this.quote = quote; this.draw = draw; this.status = status; this.proposal_updated = proposal_updated},
    }"
    x-on:update-proposal="_updateProposal($event.detail.status, $event.detail.details, $event.detail.availability, $event.detail.quote, $event.detail.draw, $event.detail.proposal_updated);"
>
    <!-- Notifications -->
    <div class="absolute -top-1 -left-3 z-[1]"
        hx-post="<?php echo site_url('/wp-html/v1/clear-notification/'); ?>"
        x-bind:hx-trigger="(!has_notification(notifications, 'new_inquiry', '<?php echo $args['proposal']['proposal_id']; ?>')) ? 'never-trigger' : 'revealed once'"
        hx-swap="beforeend"
        hx-indicator="#decoy-indicator"
        hx-vals='{"notification_type":"new_inquiry","subject_id": "<?php echo $args['proposal']['proposal_id']; ?>" }'
    >
        <span id="decoy-indicator"></span>
    </div>

    <!-- Calendar Icon -->
    <div class="w-20 sm:w-24 shrink-0">
        <?php echo get_template_part('template-parts/global/calendar/css-calendar-img', '', ['timestamp' => $args['proposal']['event']['start_date'] ? strtotime($args['proposal']['event']['start_date']) : null ]); ?>
    </div>

    <!-- Info -->
    <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">

        <!-- Title + Status -->
        <div class="flex flex-row items-start justify-between gap-2">
            <h2 class="text-18 sm:text-20 font-semibold cursor-pointer"><?php echo esc_html($args['proposal']['event']['event_name']); ?></h2>
            <div>
                <?php get_template_part('template-parts/cards/card-components/gig-status-badge', '', [
                    'status_var'  => 'status',
                    'proposal_id' => $args['proposal']['proposal_id'],
                ]); ?>
                <?php get_template_part('template-parts/cards/card-components/gig-notification-badge', '', [
                    'proposal_id' => $args['proposal']['proposal_id'],
                ]); ?>
            </div>
        </div>

        <!-- Listing and update time -->
        <?php if ($args['proposal']['listing_name']) { ?>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full overflow-hidden bg-yellow-light shrink-0">
                    <?php if ($args['proposal']['listing_thumbnail_url']) { ?>
                        <img src="<?php echo esc_url($args['proposal']['listing_thumbnail_url']); ?>" alt="<?php echo esc_attr($args['proposal']['listing_name']); ?>" class="w-full h-full object-cover" />
                    <?php } else { ?>
                        <div class="w-full h-full flex items-center justify-center text-12 font-bold text-black/40"><?php echo strtoupper(mb_substr($args['proposal']['listing_name'], 0, 1)); ?></div>
                    <?php } ?>
                </div>
                <div class="flex flex-col">
                    <span class="text-14 font-semibold"><?php echo esc_html($args['proposal']['listing_name']); ?></span>
                    <p class="text-12 text-black/50" x-show="status == 'inquiry'" x-cloak>was invited to respond on <span x-text="proposal_updated"></p>
                    <p class="text-12 text-black/50" x-show="status != 'inquiry'" x-cloak>responded on <span x-text="proposal_updated"></span></p>
                </div>
            </div>
        <?php } ?>

        <!-- Date time location -->
        <?php echo get_template_part('template-parts/cards/card-components/event-meta-line', '', [
            'start_date'     => $args['proposal']['event']['start_date'],
            'end_date'       => $args['proposal']['event']['end_date'],
            'start_time'     => $args['proposal']['event']['start_time'],
            'end_time'       => $args['proposal']['event']['end_time'],
            'address_line_1' => $args['proposal']['event']['address_line_1'] ?? '',
            'address_line_2' => $args['proposal']['event']['address_line_2'] ?? '',
            'city'           => $args['proposal']['event']['city'],
            'state'          => $args['proposal']['event']['state'],
            'zip_code'       => $args['proposal']['event']['zip_code'] ?? '',
        ]); ?>

        <!-- Details -->
        <?php if ($args['proposal']['event']['details']) { ?>
            <div class="flex flex-col min-h-[1.5rem]">
                <span class="text-12 text-black/50 font-semibold">Details</span>
                <p class="text-14"><?php echo esc_html($args['proposal']['event']['details']); ?></p>
            </div>
        <?php } ?>

        <!-- Budget + Compensation -->
        <?php if ($args['proposal']['event']['budget'] || $args['proposal']['event']['compensation']) { ?>
            <div class="flex flex-col min-h-[1.5rem]">
                <span class="text-12 text-black/50 font-semibold">Compensation</span>
                <div class="flex flex-wrap items-center gap-2 text-14">
                    <?php if ($args['proposal']['event']['budget']) { ?>
                        <span>$<?php echo esc_html(number_format((float) $args['proposal']['event']['budget'])); ?></span>
                    <?php } ?>
                    <?php if ($args['proposal']['event']['compensation']) { ?>
                        <span><?php echo esc_html($args['proposal']['event']['compensation']); ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <!-- Response -->
        <div class="flex flex-col mb-4" x-show="!showForm && status != 'inquiry'" x-cloak>
            <span class="text-12 text-black/50 font-semibold">Your Response</span>
            <div class="flex flex-wrap items-center gap-2">
                <p class="w-full text-14 mb-1 whitespace-pre-wrap" x-text="prop_details" x-show="prop_details" x-cloak></p>
                <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold capitalize" x-text="availability" x-show="availability" x-cloak></span>
                <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Quote: $${quote}`" x-show="quote" x-cloak></span>
                <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/40 font-semibold" x-text="`Draw: ${draw}`" x-show="draw" x-cloak></span>
            </div>
        </div>

        <!-- Respond (desktop) -->
        <div class="hidden sm:flex justify-start">
            <?php get_template_part('template-parts/cards/card-components/gig-card-form', '', [
                'proposal_id'   => $args['proposal']['proposal_id'],
                'request_quote' => $args['proposal']['event']['request_quote'],
                'request_draw'  => $args['proposal']['event']['request_draw'],
                'device'        => 'desktop',
            ]); ?>
        </div>

        <!-- Respond (mobile) -->
        <div class="block sm:hidden w-full">
            <?php get_template_part('template-parts/cards/card-components/gig-card-form', '', [
                'proposal_id'   => $args['proposal']['proposal_id'],
                'request_quote' => $args['proposal']['event']['request_quote'],
                'request_draw'  => $args['proposal']['event']['request_draw'],
                'device'        => 'mobile',
            ]); ?>
        </div>

    </div>

</div>
