//import L from 'leaflet'; NPM version does not work; it was loading tiles out of order; switched to loading the CDN version in functions.php
// Depends on leaflet and leaflet cluster plugin

const REPLACE_MARKERS_EVENT_NAME = 'ReplaceMarkers';
const ADD_MARKERS_EVENT_NAME = 'AddMarkers';
const ADD_MARKER_EVENT_NAME = 'AddMarker';

class LeafletMap {
    constructor() {
        // Set defaults
        this.mapCenter = [30.274271, -97.740317];
        this.zoomLevel = 10;
        this.enablePopups = true;
        this.cluster = null;

        // check for map options
        // TODO replace map init div with sending an event to init map
        var mapInitDiv = document.getElementById('map-init-div');
        if (mapInitDiv) {
            var latitude = mapInitDiv.getAttribute('latitude');
            var longitude = mapInitDiv.getAttribute('longitude');
            var initZoomLevel = mapInitDiv.getAttribute('zoom-level');
            var enablePopups = mapInitDiv.getAttribute('enable-popups');
            if (latitude && longitude) { this.mapCenter = [latitude, longitude]; }
            if (initZoomLevel) { this.zoomLevel = initZoomLevel; }
            if (enablePopups && enablePopups == "false") { this.enablePopups = false; }
        }
        // create a map for element with id leaflet-map
        this.mapElement = document.getElementById('leaflet-map');
        if (this.mapElement !== null) {
            this.map = L.map('leaflet-map', {}).setView(this.mapCenter, this.zoomLevel);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(this.map);
            //var marker = L.marker([30.274271, -97.740317]).addTo(this.map);
            this._setUpListeners();
        }
    }

    _setUpListeners() {
        document.addEventListener(REPLACE_MARKERS_EVENT_NAME, this.replaceMarkers.bind(this));
        document.addEventListener(ADD_MARKERS_EVENT_NAME, this.addMarkers.bind(this));
        document.addEventListener(ADD_MARKER_EVENT_NAME, this.addMarker.bind(this));
    }

    addMarker(evnt) {
        let marker = this.getMarker(evnt.detail.latitude, evnt.detail.longitude);
        marker.addTo(this.map);
    }

    replaceMarkers(evnt) {
        if (this.cluster) { this.map.removeLayer(this.cluster); }
        this.addMarkers(evnt);
    }

    addMarkers(evnt) {
        let markers = evnt.detail.markers
        this.cluster = this.getCluster();
        let leafletMarkers = [];
        for (let i = 0; i < markers.length; i++) {
            let latitude = markers[i].latitude;
            let longitude = markers[i].longitude;
            let coordinateTitle = markers[i].coordinateTitle;
            let coordinateLinkUrl = markers[i].coordinateLinkUrl;
            let overallRating = markers[i].overallRating;
            let averageEarnings = markers[i].averageEarnings;
            let reviewCount = markers[i].reviewCount;

            // Build marker
            let marker = this.getMarker(latitude, longitude);

            // Build Popup
            if (this.enablePopups) {
                let popupContent = `
                    <div style="margin: 0; width: 271px;">
                        <a class="map-popup-image-container" href="${coordinateLinkUrl}">
                            <div class="map-popup-headings">
                                <h3>${coordinateTitle}</h3>
                            </div>
                        </a>
                        <div class="map-popup-date-time">Average Earnings Per Gig: $${averageEarnings}</div>
                        <div class="map-popup-date-time">Review Count: ${reviewCount}</div>
                        <div class="map-popup-date-time">Rating: ${overallRating}/5</div>
                    </div>
                `;
                let popup = L.popup()
                    .setLatLng([latitude, longitude])
                    .setContent(popupContent)
                marker.bindPopup(popup);
                marker.on('mouseover', function() { this.openPopup(); });
            }

            leafletMarkers.push(marker);
        }
        this.cluster.addLayers(leafletMarkers);
        this.map.addLayer(this.cluster);
    }

    getMarker(latitude, longitude) {
        return L.marker([latitude, longitude], {
            icon: new L.DivIcon({ html: '<div class="jm-map-icon-dot"></div>', className: 'jm-map-icon', iconSize: new L.Point(30, 30) }),
            title: 'marker',
            //alt: TODO add alt text
        })
    }

    getCluster() {
        return L.markerClusterGroup({
            showCoverageOnHover: false,
            zoomToBoundsOnClick: false,
            spiderfyOnMaxZoom: true,
            removeOutsideVisibleBounds: true,
            maxClusterRadius: 40,
            spiderfyPolylineOptions: { weight: 1.5, color: '#442A88', opacity: 0.5 }, // FCECAC FFE77F EE7E7E EB4C67 B1A6CE 442A88
            iconCreateFunction: this.iconCreationFunction
        });
    }
    iconCreationFunction(cluster) {
        var childCount = cluster.getChildCount();
        return new L.DivIcon({ html: '<div><span >' + childCount + '</span></div>', className: 'jm-map-cluster-icon', iconSize: new L.Point(40, 40) });
    }
}

export default LeafletMap
