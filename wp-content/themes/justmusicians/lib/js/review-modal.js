
function showReviewSlide(alco, slide) {
    if (slide) {
        alco.showReviewModal         = true;
        alco.showReviewSlide         = false;
        alco.showReviewUserInfoSlide = false;
        alco.showReviewThankYouSlide = false;
        alco.showReviewErrorSlide    = false;
        if (slide == 'review')    { alco.showReviewSlide         = true; alco.currentReviewSlide = 'review';   alco.$nextTick(() =>  { alco.reviewProgress = Math.round((1/3) * 100); }); }
        if (slide == 'user-info') { alco.showReviewUserInfoSlide = true; alco.currentReviewSlide = 'user-info'; alco.$nextTick(() => { alco.reviewProgress = Math.round((2/3) * 100); });                    }
        if (slide == 'thankyou')  { alco.showReviewThankYouSlide = true; alco.currentReviewSlide = 'thankyou'; alco.$nextTick(() =>  { alco.reviewProgress = 100; });                     }
        if (slide == 'error')     { alco.showReviewErrorSlide    = true; alco.currentReviewSlide = 'error';    alco.$nextTick(() =>  { alco.reviewProgress = 100; });                     }
    } else {
        alco.showReviewModal  = false;
    }
}

function openReviewModal(alco, reviewType, revieweeId) {
    showReviewSlide(alco, 'review');
}

function handleCreateReviewSuccess(alco) {
    if (alco.accountSettings.display_name_is_cleaned || !alco.accountSettings.position || !alco.accountSettings.organization) {
        showReviewSlide(alco, 'user-info');
    } else {
        showReviewSlide(alco, 'thankyou');
    }
}

function handleCreateReviewError(alco, message) {
    alco.reviewErrorMsg = message;
    showReviewSlide(alco, 'error');
}

function handleUpdateAccountSettingsSuccess(alco) {
    showReviewSlide(alco, 'thankyou');
}

function handleUpdateAccountSettingsError(alco, message) {
    alco.reviewErrorMsg = message;
    showReviewSlide(alco, 'error');
}
