
<span id="request-quote-button-<?php echo $args['post_id']; ?>" class="w-full"
    x-data="{
        _addToInquiry(inquiryId, listingId) { return addToInquiry(this, inquiryId, listingId); },
    }"
    x-on:add-listing-to-inquiry="_addToInquiry($event.detail.inquiry_id, $event.detail.listing_id)"
>


    <!-- Add listing to inquiry button -->
    <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 rounded-sm font-sun-motter text-14 inline-block w-full"
        x-show="_showAddListingToInquiryButton('<?php echo $args['inquiry_id']; ?>', '<?php echo $args['post_id']; ?>')" x-cloak
        hx-post="<?php echo site_url('/wp-html/v1/inquiries/' . $args['inquiry_id'] . '/listings/' . $args['post_id']); ?>"
        hx-target="#inquiry-menu-result-<?php echo $args['post_id']; ?>"
        hx-trigger="click"
        hx-indicator="#decoy-indicator<?php echo $args['post_id']; ?>"
        hx-vals='{"listing_id": "<?php echo $args['post_id']; ?>"}'
    >Send Inquiry</button>


    <!-- Already added to inquiry state -->
    <button type="button" class="bg-navy px-3 py-3 rounded-sm font-sun-motter text-14 text-white inline-block w-full" disabled
        x-show="_showListingInInquiry('<?php echo $args['inquiry_id']; ?>', '<?php echo $args['post_id']; ?>')" x-cloak
    >Sent</button>


    <!-- Results api -->
    <span id="inquiry-menu-result-<?php echo $args['post_id']; ?>"></span>
    <span id="decoy-indicator<?php echo $args['post_id']; ?>"></span>


</span>
