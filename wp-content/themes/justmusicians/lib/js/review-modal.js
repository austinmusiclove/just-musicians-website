
function showReviewSlide(alco, slide) {
    if (slide) {
        alco.showReviewModal         = true;
        alco.showReviewSlide         = false;
        alco.showReviewUserInfoSlide = false;
        alco.showReviewThankYouSlide = false;
        alco.showReviewErrorSlide    = false;
        if (slide == 'review')   { alco.showReviewSlide         = true; alco.currentReviewSlide = 'review';   alco.$nextTick(() => { alco.reviewProgress = Math.round((1/2) * 100); }); }
        if (slide == 'userinfo') { alco.showReviewUserInfoSlide = true; alco.currentReviewSlide = 'userinfo'; alco.$nextTick(() => { alco.reviewProgress = Math.round((2/2) * 100); }); }
        if (slide == 'thankyou') { alco.showReviewThankYouSlide = true; alco.currentReviewSlide = 'thankyou'; alco.$nextTick(() => { alco.reviewProgress = 100; });                     }
        if (slide == 'error')    { alco.showReviewErrorSlide    = true; alco.currentReviewSlide = 'error';    alco.$nextTick(() => { alco.reviewProgress = 100; });                     }
    } else {
        alco.showReviewModal  = false;
    }
}

function openReviewModal(alco, reviewType, revieweeId) {
    //alco.$refs.inquiryListingInput.value = listingId;
    // set review type
    // set reviewee Id
    showReviewSlide(alco, 'review');
}
