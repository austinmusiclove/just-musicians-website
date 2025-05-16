
// Handles Inquiry Modal front end interaction

function clearInquiryForm(alco) {
    alco.$refs.inquiryForm.reset();
    alco.inquiryListing = '';
    alco.inquiryListingName = '';
    alco.inquiryDateType = '';
    alco.inquiryZipCode = '';
    alco.inquiryProgress = 0;
}

function showInquirySlide(alco, slide) {
    if (slide) {
        alco.showInquiryModal   = true;
        alco.showDateSlide      = false;
        alco.showLocationSlide  = false;
        alco.showDurationSlide  = false;
        alco.showGenreSlide  = false;
        alco.showPerformersSlide  = false;
        alco.showEquipmentSlide = false;
        alco.showDetailsSlide   = false;
        alco.showQuoteSlide     = false;
        alco.showDiscardSlide   = false;
        alco.showThankYouSlide  = false;
        alco.showErrorSlide  = false;
        if (slide == 'date')       { alco.showDateSlide       = true; alco.currentInquirySlide = 'date';       alco.$nextTick(() => { alco.inquiryProgress = Math.round((1/9) * 100); }); }
        if (slide == 'location')   { alco.showLocationSlide   = true; alco.currentInquirySlide = 'location';   alco.$nextTick(() => { alco.inquiryProgress = Math.round((2/9) * 100); alco.$refs.inquiryZipCodeInput.focus(); });  }
        if (slide == 'duration')   { alco.showDurationSlide   = true; alco.currentInquirySlide = 'duration';   alco.$nextTick(() => { alco.inquiryProgress = Math.round((3/9) * 100); }); }
        if (slide == 'genre')      { alco.showGenreSlide      = true; alco.currentInquirySlide = 'genre';      alco.$nextTick(() => { alco.inquiryProgress = Math.round((4/9) * 100); }); }
        if (slide == 'performers') { alco.showPerformersSlide = true; alco.currentInquirySlide = 'performers'; alco.$nextTick(() => { alco.inquiryProgress = Math.round((5/9) * 100); }); }
        if (slide == 'equipment')  { alco.showEquipmentSlide  = true; alco.currentInquirySlide = 'equipment';  alco.$nextTick(() => { alco.inquiryProgress = Math.round((6/9) * 100); }); }
        if (slide == 'details')    { alco.showDetailsSlide    = true; alco.currentInquirySlide = 'details';    alco.$nextTick(() => { alco.inquiryProgress = Math.round((7/9) * 100); alco.$refs.inquiryDetails.focus(); }); }
        if (slide == 'quotes')     { alco.showQuoteSlide      = true; alco.currentInquirySlide = 'quotes';     alco.$nextTick(() => { alco.inquiryProgress = Math.round((8/9) * 100); }); }
        if (slide == 'thankyou')   { alco.showThankYouSlide   = true; alco.currentInquirySlide = 'thankyou';   alco.$nextTick(() => { alco.inquiryProgress = 100; });                     }
        if (slide == 'error')      { alco.showErrorSlide      = true; alco.currentInquirySlide = 'error';      alco.$nextTick(() => { alco.inquiryProgress = 100; });                     }
        if (slide == 'discard')    { alco.showDiscardSlide    = true; }
    } else {
        alco.showInquiryModal  = false;
    }
}

function openInquiryModal(alco, listingId, listingName) {
    alco.inquiryListing = listingId;
    alco.inquiryListingName = listingName;
    showInquirySlide(alco, 'date');
}

function exitInquiryModal(alco) {
    if (alco.currentInquirySlide == 'thankyou' || alco.currentInquirySlide == 'error') {
        showInquirySlide(alco, '');
    } else {
        showInquirySlide(alco, 'discard');
    }
}

function handleCreateInquirySuccess(alco) {
    showInquirySlide(alco, 'thankyou');
    clearInquiryForm(alco);
}
function handleCreateInquiryError(alco) {
    showInquirySlide(alco, 'error');
    clearInquiryForm(alco);
}
