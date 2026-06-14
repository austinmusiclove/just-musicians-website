
<span id="request-quote-button-<?php echo $args['post_id']; ?>" class="w-full"
    x-data="{
        _addListingToEvent(eventId, listingId) { return addListingToEvent(this, eventId, listingId); },
    }"
    x-on:add-listing-to-event="_addListingToEvent($event.detail.event_id, $event.detail.listing_id)"
>


    <!-- Request proposal button -->
    <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 rounded-sm font-sun-motter text-14 inline-block w-full"
        x-show="_showRequestProposalButton('<?php echo $args['event_id']; ?>', '<?php echo $args['post_id']; ?>')" x-cloak
        hx-post="<?php echo site_url('/wp-html/v1/events/request-proposal/' . $args['event_id'] . '/listings/' . $args['post_id']); ?>"
        hx-target="#inquiry-menu-result-<?php echo $args['post_id']; ?>"
        hx-trigger="click"
        hx-indicator="#decoy-indicator<?php echo $args['post_id']; ?>"
        hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
    >Send Inquiry</button>


    <!-- Already has proposal for this event state -->
    <button type="button" class="bg-navy px-3 py-3 rounded-sm font-sun-motter text-14 text-white inline-block w-full" disabled
        x-show="_showListingInEvent('<?php echo $args['event_id']; ?>', '<?php echo $args['post_id']; ?>')" x-cloak
    >Sent</button>


    <!-- Results api -->
    <span id="inquiry-menu-result-<?php echo $args['post_id']; ?>"></span>
    <span id="decoy-indicator<?php echo $args['post_id']; ?>"></span>


</span>
