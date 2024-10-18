import axios from 'axios';

const VENUE_REVIEWS_CONTAINER_ID = 'venue-reviews-container';
const GET_VENUE_REVIEWS_EVENT_NAME = 'GetVenueReviews';
const GET_VENUE_REVIEWS_API_URL = `${siteData.root_url}/wp-json/v1/venue_reviews`;

class VenueDataManager {
    constructor() {
        this._setupElements()
        this._setupEventListeners()
    }

    _setupElements() {
        this.venueReviewsContainer = document.getElementById(VENUE_REVIEWS_CONTAINER_ID);
    }
    _setupEventListeners() {
        document.addEventListener(GET_VENUE_REVIEWS_EVENT_NAME, this.getVenueReviews.bind(this));
    }

    getVenueReviews(evnt) {
        let venueId = evnt.detail.venueId;
        this.getVenueReviewsFromServer(venueId).then((response) => {
            return response.data;
        }).then((data) => {
            let reviewsHtml = ''
            for (let iterator = 0; iterator < data.length; iterator++) {
                reviewsHtml += this.getVenueReviewHtml(data[iterator])
            }
            this.venueReviewsContainer.innerHTML = reviewsHtml
        }).catch((err) => {
            console.warn(err);
        });
    }

    getVenueReviewsFromServer(venueId) {
        return axios.get(`${GET_VENUE_REVIEWS_API_URL}/?venue_id=${venueId}`);
    }

    getVenueReviewHtml(venueReview) {
        let html = `
            <h3>${venueReview.overall_rating}/5 - Anonymous Performer</h3>
            <p>
                Compensation Type: ${venueReview.comp_types_string}
                <br>Hours Performed: ${venueReview.hours_performed}
                <br>Total Performers: ${venueReview.total_performers}`
        if (venueReview.has_guarantee_comp) { html += `<br>Guarantee: $${venueReview.guarantee_earnings}`; }
        if (venueReview.has_door_comp) { html += `<br>Door: $${venueReview.door_earnings} (${venueReview.door_percentage}%)`; }
        if (venueReview.has_sales_comp) { html += `<br>Sales: $${venueReview.sales_earnings} (${venueReview.sales_percentage}%)`; }
        if (venueReview.has_tips_comp) { html += `<br>Tips: $${venueReview.tips_earnings}`; }
        html += `
                <br>Total Earnings: $${venueReview.total_earnings}
            </p>
            <p>${venueReview.review}</p>`
        return html;
    }
}

export default VenueDataManager
