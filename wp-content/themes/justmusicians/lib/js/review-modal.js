
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

document.addEventListener("DOMContentLoaded", () => {
    const starContainer = document.getElementById("rating-stars");
    const stars = starContainer.querySelectorAll(".star");
    const ratingInput = document.getElementById("rating-input");

    function fillStars(amount) {
        stars.forEach((star, index) => {
            const svg = star.querySelector("svg");
            const fill = (index < amount) ? 100 : 0;

            // Update the clip-path % of the foreground path
            const fillGroup = svg.querySelector("g");
            fillGroup.style.clipPath = `inset(0 ${100 - fill}% 0 0)`;
        });
    }

    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            const hoverValue = parseInt(star.dataset.value, 10);
            fillStars(hoverValue);
        });

        star.addEventListener("click", () => {
            const clickedValue = parseInt(star.dataset.value, 10);
            starContainer.dataset.selected = clickedValue;
            ratingInput.value = clickedValue;
            fillStars(clickedValue);
        });
    });

    starContainer.addEventListener("mouseleave", () => {
        const selected = parseInt(starContainer.dataset.selected, 10);
        fillStars(selected);
    });
});
