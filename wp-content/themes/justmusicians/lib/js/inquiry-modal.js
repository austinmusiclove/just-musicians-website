
// Handles Inquiry Modal front end interaction

function clearInquiryForm(alpineComponent) {
    alpineComponent.$refs.inquiryForm.reset();
    alpineComponent.$refs.inquiryListing.value = '';
    alpineComponent.inquiryDate = '';
    alpineComponent.inquiryZipCode = '';
}

function showInquirySlide(alpineComponent, slide) {
    if (slide) {
        alpineComponent.showInquiryModal  = true;
        alpineComponent.showDateSlide     = false;
        alpineComponent.showLocationSlide = false;
        alpineComponent.showDetailsSlide  = false;
        alpineComponent.showEmailSlide    = false;
        alpineComponent.showDiscardSlide  = false;
        alpineComponent.showThankYouSlide = false;
        if (slide == 'date')     { alpineComponent.showDateSlide     = true; alpineComponent.currentInquirySlide = 'date'; }
        if (slide == 'location') { alpineComponent.showLocationSlide = true; alpineComponent.currentInquirySlide = 'location';}
        if (slide == 'details')  { alpineComponent.showDetailsSlide  = true; alpineComponent.currentInquirySlide = 'details';}
        if (slide == 'email')    { alpineComponent.showEmailSlide    = true; alpineComponent.currentInquirySlide = 'email';}
        if (slide == 'thankyou') { alpineComponent.showThankYouSlide = true; alpineComponent.currentInquirySlide = 'thankyou';}
        if (slide == 'discard')  { alpineComponent.showDiscardSlide  = true; }
    } else {
        alpineComponent.showInquiryModal  = false;
    }
}

function openInquiryModal(alpineComponent, listingId) {
    alpineComponent.$refs.inquiryListing.value = listingId;
    if (!alpineComponent.inquiryDate) {
        showInquirySlide(alpineComponent, 'date');
    } else if (!alpineComponent.inquiryZipCode) {
        showInquirySlide(alpineComponent, 'location');
    } else {
        showInquirySlide(alpineComponent, 'details');
    }
}
