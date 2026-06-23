
<span id="request-quote-button-<?php echo $args['listing_id']; ?>" class="sm:absolute sm:right-0 sm:bottom-4 w-full sm:w-fit sm:min-w-32"
    x-data="{
        _addListingToEvent(eventId, listingId) { return addListingToEvent(this, eventId, listingId); },
    }"
    x-on:add-listing-to-event="_addListingToEvent($event.detail.event_id, $event.detail.listing_id)"
>


    <!-- Request proposal button -->
    <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 rounded-sm font-sun-motter text-14 inline-block w-full"
        x-show="_showRequestProposalButton('<?php echo $args['event_id']; ?>', '<?php echo $args['listing_id']; ?>')" x-cloak
        hx-post="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/listings/' . $args['listing_id']); ?>/request-proposal/"
        hx-target="#inquiry-menu-result-<?php echo $args['listing_id']; ?>"
        hx-trigger="click"
        hx-indicator="#request-proposal-button-content-<?php echo $args['listing_id'] . '-' . $args['event_id']; ?>"
    >
        <span id="request-proposal-button-content-<?php echo $args['listing_id'] . '-' . $args['event_id']; ?>" class="flex justify-center">
            <span class="htmx-indicator-component-block-replace">Send Inquiry</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </span>
    </button>


    <!-- Already has proposal for this event state -->
    <button type="button" class="bg-navy px-3 py-3 rounded-sm font-sun-motter text-14 text-white inline-block w-full" disabled
        x-show="!_showRequestProposalButton('<?php echo $args['event_id']; ?>', '<?php echo $args['listing_id']; ?>')" x-cloak
    >Sent</button>


    <!-- Results api -->
    <span id="inquiry-menu-result-<?php echo $args['listing_id']; ?>"></span>


</span>
