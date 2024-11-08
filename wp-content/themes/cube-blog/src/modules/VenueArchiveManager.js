import axios from 'axios';

const GET_VENUES_API_URL = siteData.venues_api_url;
const GET_VENUE_REVIEWS_BATCH_API_URL = siteData.venue_reviews_batch_api_url;
const REPLACE_MARKERS_EVENT_NAME = 'ReplaceMarkers';
const PAY_METRIC_LABELS = {
    'average_earnings': 'Avg. Earnings Per Gig',
    'average_earnings_per_performer': 'Avg. Earnings Per Gig Per Performer',
    'average_earnings_per_hour': 'Avg. Earnings Per Hour',
    'average_earnings_per_performer_per_hour': 'Avg. Earnings Per Performer Per Hour',
};

class VenueArchiveManager {
    constructor(venueInsightGenerator) {
        if (this._setupElements()) {
            this._setupState();
            this._setupEventListeners();
            this._initialize();
            this.venueInsightGenerator = venueInsightGenerator;
        }
    }
    _setupState() {
        this.currentSearchVenues = [];
        this.venueReviews = [];
        this.venueData = {};
        this.payMetric = this.payMetricElement.value || null;
        this.payStructure = this.payStructureElement.value || null;
        this.firstRender = true;
    }
    _setupElements() {
        this.payStructureElement = document.getElementById('pay-structure');
        if (this.payStructureElement == null) { return false; }
        this.payMetricElement = document.getElementById('pay-metric');
        this.tableElement = document.getElementById('top-venues-table');
        return true;
    }
    _setupEventListeners() {
        this.payStructureElement.addEventListener('change', this.updateTableAndMap.bind(this));
        this.payMetricElement.addEventListener('change', this.updateTableAndMap.bind(this));
    }
    _initialize() {
        document.addEventListener("DOMContentLoaded", () => {
            this.getVenues();
        });
    }

    getVenues() {
        this.clearData();
        this.addSpinners();
        this.getVenuesFromServer().then((response) => {
            return response.data;
        }).then((data) => {
            this.currentSearchVenues = data;
            this.updateTableAndMap(this.currentSearchVenues);
            return this.venueInsightGenerator.addVenues(this.currentSearchVenues.map((venue) => venue.ID));
        }).then(() => {
            this.enableFilters();
            this.removeSpinners();
        }).catch((err) => {
            console.warn(err);
        });
    }
    getVenuesFromServer() {
        return axios.get(GET_VENUES_API_URL);
    }

    updateTableAndMap() {
        let tableVenues = []
        this.payMetric = this.payMetricElement.value || 'average_earnings';
        this.payStructure = this.payStructureElement.value || null;

        if (this.firstRender) {
            this.firstRender = false
            tableVenues = this.currentSearchVenues;
        } else {
            for (let iterator = 0; iterator < this.currentSearchVenues.length; iterator++) {
                let venue = this.currentSearchVenues[iterator];
                if (this.venueInsightGenerator.getInsight(venue.ID, 'review_count', this.payStructure) > 0) {
                    let metric = this.venueInsightGenerator.getInsight(venue.ID, this.payMetric, this.payStructure);
                    venue[this.payMetric] = metric;
                    tableVenues.push(venue);
                }
            }
        }
        tableVenues.sort((item1, item2) => item2[this.payMetric] - item1[this.payMetric]);

        // update table and markers
        let markers = []
        let tableHtml = this.getTopVenuesTableHeaderHtml();
        for (let iterator = 0; iterator < tableVenues.length; iterator++) {
            tableHtml += this.getTopVenuesTableRowHtml(iterator+1, tableVenues[iterator])
            markers.push(this.getMarkerData(tableVenues[iterator]));
        }
        document.dispatchEvent(new CustomEvent(REPLACE_MARKERS_EVENT_NAME, {'detail': { 'markers': markers}}));
        this.tableElement.innerHTML = tableHtml;
    }

    clearData(){ } // clears existing venue data on the page
    addSpinners() { } // adds elements that show the new content is loading
    removeSpinners() { } // removes elements that show that content is loading
    enableFilters() {
        this.payMetricElement.disabled = false;
        this.payStructureElement.disabled = false;
    }
    // returns promise for venue data from the venues api
    getTopVenuesTableHeaderHtml() {
        return `<tr>
                    <th>Rank</th>
                    <th>Venue</th>
                    <th>${PAY_METRIC_LABELS[this.payMetric]}</th>
                    <th>Review Count</th>
                    <th>Rating</th>
                </tr>`
    }
    getTopVenuesTableRowHtml(rank, venue) {
        return `<tr>
                    <td>${rank}</td>
                    <td><a href="${venue.permalink}">${venue.name}</a></td>
                    <td>$${venue[this.payMetric]}</td>
                    <td>${venue.review_count}</td>
                    <td>${venue.overall_rating}/5</td>
                </tr>`
    }
    getMarkerData(venue) {
        return {
            'coordinateTitle': venue.name,
            'latitude': venue.latitude,
            'longitude': venue.longitude,
            'coordinateLinkUrl': venue.permalink,
            'averageEarnings': venue.average_earnings,
            'reviewCount': venue.review_count,
            'overallRating': venue.overall_rating,
        }
    }

}

export default VenueArchiveManager

