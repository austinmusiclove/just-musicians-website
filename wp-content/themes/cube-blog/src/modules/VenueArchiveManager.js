import axios from 'axios';

const REPLACE_MARKERS_EVENT_NAME = 'ReplaceMarkers';
const GET_VENUES_EVENT_NAME = 'GetVenues';
const PAY_METRIC_LABELS = {
    'average_earnings': 'Avg. Earnings Per Gig',
    'average_earnings_per_performer': 'Avg. Earnings Per Gig Per Performer',
    'average_earnings_per_hour': 'Avg. Earnings Per Hour',
    'average_earnings_per_performer_per_hour': 'Avg. Earnings Per Performer Per Hour',
};

class VenueArchiveManager {
    constructor() {
        this._setupEventListeners();
    }
    _setupEventListeners() {
        document.addEventListener(GET_VENUES_EVENT_NAME, this.getVenues.bind(this));
    }

    getVenues(evnt) {
        let payMetric = evnt.detail.payMetric;
        let payType = evnt.detail.payType;
        let tableElement = document.getElementById(evnt.detail.tableId);
        this.clearData();
        this.addSpinners();
        this.getVenuesFromServer(payMetric, payType).then((response) => {
            this.removeSpinners();
            return response.data;
        }).then((data) => {
            let markers = []
            let tableHtml = this.getTopVenuesTableHeaderHtml(payMetric);
            for (let iterator = 0; iterator < data.length; iterator++) {
                markers.push(this.getMarkerData(data[iterator]));
                tableHtml += this.getTopVenuesTableRowHtml(iterator+1, data[iterator])
            }
            document.dispatchEvent(new CustomEvent(REPLACE_MARKERS_EVENT_NAME, {'detail': { 'markers': markers}}));
            tableElement.innerHTML = tableHtml;
        }).catch((err) => {
            console.warn(err);
        });
    }

    clearData(){ } // clears existing venue data on the page
    addSpinners() { } // adds elements that show the new content is loading
    removeSpinners() { } // removes elements that show that content is loading
    // returns promise for venue data from the venues api
    getVenuesFromServer(payMetric='_average_earnings', payType=null) {
        let url = `${siteData.venues_api_url}/?pay_metric=${payMetric}`
        if (payType) { url += `&pay_type=${payType}`}
        return axios.get(url);
    }
    getTopVenuesTableHeaderHtml(payMetric) {
        return `<tr>
                    <th>Rank</th>
                    <th>Venue</th>
                    <th>${PAY_METRIC_LABELS[payMetric]}</th>
                    <th>Review Count</th>
                    <th>Rating</th>
                </tr>`
    }
    getTopVenuesTableRowHtml(rank, venue) {
        return `<tr>
                    <td>${rank}</td>
                    <td><a href="${venue.permalink}">${venue.name}</a></td>
                    <td>$${venue.pay_metric}</td>
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

