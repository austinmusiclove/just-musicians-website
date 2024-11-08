import axios from 'axios';

const GET_VENUE_REVIEWS_BATCH_API_URL = siteData.venue_reviews_batch_api_url;

class VenueInsightGenerator {
    constructor() {
        this._setupState();
    }
    _setupState() {
        this.venueIds = [];
        this.venueReviews = {};
        this.venueReviewBatchSize = 100;
    }

    getInsight(venueId, metric, payStructure) {
        if (this.venueIds.includes(venueId)) {
            let reviews = this.venueReviews[venueId];
            if (payStructure) { reviews = reviews.filter((item) => item['comp_structure_string'] == payStructure) };
            return this.calculateMetric(reviews, metric);
        } else {
            return null;
        }
    }

    addVenues(venueIds) {
        this.ready = false;
        let difference = venueIds.filter(item => !this.venueIds.includes(item));
        this.venueIds.push(...difference);
        return this.getVenueReviews(difference);
    }
    getVenueReviews(venueIds) {
        let batches = this.getVenueIdBatches(venueIds);
        let promises = batches.map(batch => {
            return this.getVenueReviewsFromServer(batch).then(response => {
                return response.data;
            }).then(data => {
                for (let iterator2 = 0; iterator2 < data.length; iterator2++) {
                    let review = data[iterator2];
                    let venueId = review['venue'];
                    if (!this.venueReviews.hasOwnProperty(venueId)) { this.venueReviews[venueId] = []; }
                    this.venueReviews[venueId].push(review);
                }
            }).catch((err) => {
                console.warn(err);
            });
        });
            //for (let iterator = 0; iterator < batches.length; iterator++) {
            //}
        return Promise.all(promises);
    }
    getVenueIdBatches(venueIds) {
        let batches = [];
        for (let iterator = 0; iterator < venueIds.length; iterator += this.venueReviewBatchSize) {
            batches.push(venueIds.slice(iterator, iterator + this.venueReviewBatchSize));
        }
        return batches;
    }
    getVenueReviewsFromServer(venueIds) {
        return axios.get(`${GET_VENUE_REVIEWS_BATCH_API_URL}/?venue_ids=${venueIds.join(',')}`);
    }

    calculateMetric(reviews, metric) {
        if (metric == 'review_count') { return reviews.length; }
        if (metric == 'average_earnings') { return this.calculateAverage(reviews.map((review) => review['total_earnings'])); }
        if (metric == 'average_earnings_per_performer') { return this.calculateAverage(reviews.map((review) => review['earnings_per_performer'])); }
        if (metric == 'average_earnings_per_hour') { return this.calculateAverage(reviews.map((review) => review['earnings_per_hour'])); }
        if (metric == 'average_earnings_per_performer_per_hour') { return this.calculateAverage(reviews.map((review) => review['earnings_per_performer_per_hour'])); }
        return null;
    }
    calculateAverage(numbers) {
        return numbers.map(item => parseFloat(item)).reduce((sum, num) => sum + num, 0) / numbers.length;
    }
}

export default VenueInsightGenerator;
