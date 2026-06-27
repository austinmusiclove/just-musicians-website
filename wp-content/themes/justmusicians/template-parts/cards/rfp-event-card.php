<div class="flex items-center gap-3 py-3 border-b border-black/20 overflow-hidden"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/rfp-events/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#request-slide-results"
        hx-indicator="#rfp-paging-spinner"
    <?php } ?>
    x-data="{
        listingId:          inquiryListing,
        proposalListingIds: <?php echo clean_arr_for_doublequotes($args['proposal_listing_ids']); ?>,
        proposalSent() { return this.proposalListingIds.includes(this.listingId); },
    }"
>

    <div class="flex gap-1 justify-between w-full">

        <div class="flex flex-col gap-1 min-w-0">
            <h2 class="font-bold text-18 sm:text-22 break-words">
                <?php echo esc_html($args['event_name']); ?>
            </h2>
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
        </div>

        <div class="w-fit sm:min-w-22" x-show="!proposalSent()" x-cloak>
            <?php echo get_template_part('template-parts/cards/card-components/request-proposal-btn', '', [
                'event_id'       => $args['event_id'],
                'listing_id_var' => 'inquiryListing',
                'btn_text'       => 'Send',
            ]); ?>
        </div>
        <div class="w-fit sm:min-w-22" x-show="proposalSent()" x-cloak>
            <?php echo get_template_part('template-parts/cards/card-components/request-proposal-btn-sent', '', [
                'btn_text' => 'Sent',
            ]); ?>
        </div>

    </div>

</div>
