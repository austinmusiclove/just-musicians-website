
// Handles Inquiry Modal front end interaction

function clearInquiryForm(alco) {
    alco.$refs.inquiryForm.reset();
    alco.inquiryListing      = '';
    alco.inquiryListingName  = '';
    alco.inquiryErrorMsg     = '';
    alco.inquiryProgress     = 0;
}

function showInquirySlide(alco, slide) {
    if (slide) {
        alco.showInquiryModal    = true;
        alco.showDateSlide       = false;
        alco.showLocationSlide   = false;
        alco.showBudgetSlide     = false;
        alco.showGenreSlide      = false;
        alco.showPerformersSlide = false;
        alco.showDetailsSlide    = false;
        alco.showQuoteSlide      = false;
        alco.showThankYouSlide   = false;
        alco.showErrorSlide      = false;
        alco.showDiscardSlide    = false;
        if (slide == 'date')       { alco.showDateSlide       = true; alco.currentInquirySlide = 'date';       alco.$nextTick(() => { alco.inquiryProgress = Math.round((1/8) * 100); }); }
        if (slide == 'location')   { alco.showLocationSlide   = true; alco.currentInquirySlide = 'location';   alco.$nextTick(() => { alco.inquiryProgress = Math.round((2/8) * 100); alco.$refs.inquiryZipCodeInput.focus(); });  }
        if (slide == 'budget')     { alco.showBudgetSlide     = true; alco.currentInquirySlide = 'budget';     alco.$nextTick(() => { alco.inquiryProgress = Math.round((3/8) * 100); }); }
        if (slide == 'genre')      { alco.showGenreSlide      = true; alco.currentInquirySlide = 'genre';      alco.$nextTick(() => { alco.inquiryProgress = Math.round((4/8) * 100); }); }
        if (slide == 'performers') { alco.showPerformersSlide = true; alco.currentInquirySlide = 'performers'; alco.$nextTick(() => { alco.inquiryProgress = Math.round((5/8) * 100); }); }
        if (slide == 'details')    { alco.showDetailsSlide    = true; alco.currentInquirySlide = 'details';    alco.$nextTick(() => { alco.inquiryProgress = Math.round((6/8) * 100); alco.$refs.inquirySubject.focus(); }); }
        if (slide == 'quotes')     { alco.showQuoteSlide      = true; alco.currentInquirySlide = 'quotes';     alco.$nextTick(() => { alco.inquiryProgress = Math.round((7/8) * 100); }); }
        if (slide == 'thankyou')   { alco.showThankYouSlide   = true; alco.currentInquirySlide = 'thankyou';   alco.$nextTick(() => { alco.inquiryProgress = 100; });                     }
        if (slide == 'error')      { alco.showErrorSlide      = true; alco.currentInquirySlide = 'error';      alco.$nextTick(() => { alco.inquiryProgress = 100; });                     }
        if (slide == 'discard')    { alco.showDiscardSlide    = true; }
    } else {
        alco.showInquiryModal  = false;
    }
}

function openInquiryModal(alco, listingId, listingName) {
    alco.$refs.inquiryListingInput.value = listingId;
    alco.inquiryListing = listingId;
    alco.inquiryListingName = listingName;
    showInquirySlide(alco, 'date');
}

function tryExitInquiryModal(alco) {
    if (alco.currentInquirySlide == 'thankyou' || alco.currentInquirySlide == 'error') {
        exitInquiryModal(alco);
    } else if (alco.showDiscardSlide == true) {
        showInquirySlide(alco, alco.currentInquirySlide);
    } else {
        showInquirySlide(alco, 'discard');
    }
}
function exitInquiryModal(alco) {
    showInquirySlide(alco, '');
    clearInquiryForm(alco);
}

function handleCreateInquirySuccess(alco, inquiryId) {
    alco.newInquiryId = inquiryId;
    showInquirySlide(alco, 'thankyou');
}

function handleCreateInquiryError(alco, message) {
    alco.inquiryErrorMsg = message;
    showInquirySlide(alco, 'error');
}
