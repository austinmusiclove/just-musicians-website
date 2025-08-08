// Handles front end inquire buttons interactions
// Server interaction handled by htmx

function showAddListingToInquiryButton(alco, inquiryId, listingId) {
    return !alco.$data.inquiriesMap[inquiryId].listings.includes(listingId);
}

function showListingInInquiry(alco, inquiryId, listingId) {
    return alco.$data.inquiriesMap[inquiryId].listings.includes(listingId);
}

function addToInquiry(alco, inquiryId, listingId) {
    var inquiry = alco.$data.inquiriesMap[inquiryId];
    if (inquiry && !inquiry.listings.includes(listingId)) {
        alco.$data.inquiriesMap[inquiryId].listings.push(listingId);
    }
}

function addInquiry(alco, post_id, subject, listings, permalink) {
    alco.$data.inquiriesMap[post_id] = {
        'post_id':   post_id,
        'subject':   subject,
        'listings':  listings,
        'permalink': permalink,
    };
}

function resetInquiriesMenu(alco, listingId) {
    alco.showInquiriesMenu = false;
    alco.inquirySearchQuery = '';
    alco.$refs['inquiriesList' + listingId].scrollTop = 0;
}

function getSortedInquiries(alco) {
    return Object.values(alco.$data.inquiriesMap).sort((a, b) => b.post_id - a.post_id);
}
