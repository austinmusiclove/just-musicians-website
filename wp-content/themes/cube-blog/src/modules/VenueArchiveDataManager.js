import axios from 'axios';

const LEAFLET_MAP_CONTAINER_ID = 'leaflet-map';
const TOP_VENUES_TABLE_ID = 'top-venues-table';
const REPLACE_MARKERS_EVENT_NAME = 'ReplaceMarkers';
const GET_VENUES_EVENT_NAME = 'GetVenues';
const GET_VENUES_API_URL = `${siteData.root_url}/wp-json/v1/venues`;

class VenueArchiveDataManager {
    constructor() {
        console.log('construct arch data man');
        this._setupElements();
        this._setupEventListeners();
        console.log('end construct arch data man');
    }

    _setupElements() {
        console.log('set up elements');
        this.leafletMap = document.getElementById(LEAFLET_MAP_CONTAINER_ID);
        this.topVenuesTable = document.getElementById(TOP_VENUES_TABLE_ID);
        console.log('set up elements end');
    }
    _setupEventListeners() {
        console.log('set up list');
        document.addEventListener(GET_VENUES_EVENT_NAME, this.getVenues.bind(this));
        console.log('set up list end');
    }

    getVenues(evnt) {
        console.log('get venues');
        let payMetric = evnt.detail.payMetric;
        let payStructure = evnt.detail.payStructure;
        this.clearData();
        this.addSpinners();
        this.getVenuesFromServer(payMetric, payStructure).then((response) => {
            this.removeSpinners();
            return response.data;
        }).then((data) => {
            let markers = []
            let tableHtml = this.getTopVenuesTableHeaderHtml();
            for (let iterator = 0; iterator < data.length; iterator++) {
                markers.push(this.getMarkerData(data[iterator]));
                tableHtml += this.getTopVenuesTableRowHtml(iterator+1, data[iterator])
            }
            document.dispatchEvent(new CustomEvent(REPLACE_MARKERS_EVENT_NAME, {'detail': { 'markers': markers}}));
            this.topVenuesTable.innerHTML = tableHtml;
        }).catch((err) => {
            console.warn(err);
        });
    }

    clearData(){ } // clears existing venue data on the page
    addSpinners() { } // adds elements that show the new content is loading
    removeSpinners() { } // removes elements that show that content is loading
    // returns promise for venue data from the venues api
    getVenuesFromServer(payMetric='_average_earnings', payStructure=null) {
        console.log('call api');
        return axios.get(`${GET_VENUES_API_URL}/?payMetric=${payMetric}`);
    }
    getTopVenuesTableHeaderHtml() {
        return `<tr>
                    <th>Rank</th>
                    <th>Venue</th>
                    <th>Average Earnings per Gig</th>
                    <th>Review Count</th>
                    <th>Rating</th>
                </tr>`
    }
    getTopVenuesTableRowHtml(rank, venue) {
        return `<tr>
                    <td>${rank}</td>
                    <td><a href="${venue.permalink}">${venue.name}</a></td>
                    <td>$${venue.average_earnings}</td>
                    <td>${venue.review_count}</td>
                    <td>${venue.overall_rating}/5</td>
                </tr>`
    }
    getMarkerData(venue) {
        return {
            'coordinateTitle': venue.name,
            'latitude': venue.latitude,
            'longitude': venue.longitude,
            'reviewCount': venue.review_count,
            'coordinateLinkUrl': venue.permalink,
            'averageEarnings': venue.average_earnings,
            'overallRating': venue.overall_rating,
        }
    }

}

export default VenueArchiveDataManager

