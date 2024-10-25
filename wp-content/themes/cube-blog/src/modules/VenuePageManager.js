import axios from 'axios';

const GET_VENUE_REVIEWS_EVENT_NAME = 'GetVenueReviews';
const GET_VENUE_REVIEWS_API_URL = `${siteData.root_url}/wp-json/v1/venue_reviews`;

class VenuePageManager {
    constructor(chartGenerator) {
        this.chartGenerator = chartGenerator;
        this._setupEventListeners()
    }
    _setupEventListeners() {
        document.addEventListener(GET_VENUE_REVIEWS_EVENT_NAME, this.getVenueReviews.bind(this));
    }

    getVenueReviews(evnt) {
        let venueId = evnt.detail.venueId;
        this.getVenueReviewsFromServer(venueId).then((response) => {
            return response.data;
        }).then((data) => {
            // parse data
            let reviewsHtml = ''
            let payStructureChartData = {'Guarantee': 0, 'Door Deal': 0, 'Bar Deal': 0, 'Tips': 0};
            let payMethodChartData = {'Cash': 0, 'Check': 0, 'Direct Deposit': 0, 'Zelle': 0, 'PayPal': 0, 'Venmo/Cash App': 0, 'Other': 0}
            let paySpeedChartData = {'In Advance': 0, 'Day of': 0, '< 3 Days': 0, '< 1 Week': 0, '< 2 Weeks': 0, '< 30 Days': 0, 'Never': 0};
            let paySpeedStringTable = {
                'Before the gig': 'In Advance',
                'On the day of the gig': 'Day of',
                'Within 3 days after the gig': '< 3 Days',
                'Within a week after the gig': '< 1 Week',
                'Within 2 weeks after the gig': '< 2 Weeks',
                'Within 30 days after the gig': '< 30 Days',
                'Never got paid': 'Never',
            }
            for (let iterator = 0; iterator < data.length; iterator++) {
                reviewsHtml += this.getVenueReviewHtml(data[iterator])
                let payMethod = data[iterator].payment_method;
                let paySpeed = paySpeedStringTable[data[iterator].payment_speed];
                payStructureChartData['Guarantee'] += parseFloat(data[iterator].guarantee_earnings);
                payStructureChartData['Door Deal'] += parseFloat(data[iterator].door_earnings);
                payStructureChartData['Bar Deal'] += parseFloat(data[iterator].bar_earnings);
                payStructureChartData['Tips'] += parseFloat(data[iterator].tips_earnings);
                payMethodChartData.hasOwnProperty(payMethod) ? payMethodChartData[payMethod] += 1 : payMethodChartData['Other'] += 1;
                paySpeedChartData[paySpeed] += 1;
            }

            // reviews section
            let reviewsContainer = document.getElementById(evnt.detail.reviewsContainerId);
            reviewsContainer.innerHTML = reviewsHtml

            // charts section
            this.chartGenerator.generatePolarAreaChart(evnt.detail.payStructureChartContainerId, 'Pay Structure', Object.keys(payStructureChartData), Object.values(payStructureChartData));
            this.chartGenerator.generatePolarAreaChart(evnt.detail.payMethodChartContainerId, 'Payout Method', Object.keys(payMethodChartData), Object.values(payMethodChartData));
            this.chartGenerator.generatePolarAreaChart(evnt.detail.paySpeedChartContainerId, 'Payout Speed', Object.keys(paySpeedChartData), Object.values(paySpeedChartData));


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
                <br>Hours Performed: ${venueReview.hours_performed}
                <br>Total Performers: ${venueReview.total_performers}`
        if (venueReview.has_guarantee_comp) { html += `<br>Guarantee: $${venueReview.guarantee_earnings}`; }
        if (venueReview.has_door_comp) { html += `<br>Door: $${venueReview.door_earnings} (${venueReview.door_percentage}%)`; }
        if (venueReview.has_bar_comp) { html += `<br>Sales: $${venueReview.bar_earnings} (${venueReview.bar_percentage}%)`; }
        if (venueReview.has_tips_comp) { html += `<br>Tips: $${venueReview.tips_earnings}`; }
        html += `
                <br>Total Earnings: $${venueReview.total_earnings}
            </p>
            <p>${venueReview.review}</p>`
        return html;
    }
}

export default VenuePageManager