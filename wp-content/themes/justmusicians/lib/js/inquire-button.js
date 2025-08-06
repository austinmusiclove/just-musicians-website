// Handles front end inquire buttons interactions
// Server interaction handled by htmx

function showAddListingToInquiryButton(alpineComponent, inquiryId, listingId) {
    return !alpineComponent.$data.inquiriesMap[inquiryId].listings.includes(listingId);
}

function showListingInInquiry(alpineComponent, inquiryId, listingId) {
    return alpineComponent.$data.inquiriesMap[inquiryId].listings.includes(listingId);
}

function addToInquiry(alpineComponent, inquiryId, listingId) {
    var inquiry = alpineComponent.$data.inquiriesMap[inquiryId];
    if (inquiry && !inquiry.listings.includes(listingId)) {
        alpineComponent.$data.inquiriesMap[inquiryId].listings.push(listingId);
    }
}

function addInquiry(alpineComponent, post_id, subject, listings, permalink) {
    alpineComponent.$data.inquiriesMap[post_id] = {
        'post_id':   post_id,
        'subject':   subject,
        'listings':  listings,
        'permalink': permalink,
    };
}

function resetInquiriesMenu(alpineComponent, listingId) {
    alpineComponent.showInquiriesMenu = false;
    alpineComponent.inquirySearchQuery = '';
    alpineComponent.$refs['inquiriesList' + listingId].scrollTop = 0;
}

function getSortedInquiries(alpineComponent) {
    return Object.values(alpineComponent.$data.inquiriesMap).sort((a, b) => b.post_id - a.post_id);
}
