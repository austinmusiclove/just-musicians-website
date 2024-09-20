//import L from 'leaflet'; NPM version does not work; it was loading tiles out of order; switched to loading the CDN version in functions.php

class LeafletMap {
    constructor() {
        // Set defaults
        this.mapCenter = [30.274271, -97.740317];
        this.zoomLevel = 10;
        this.enablePopups = true;

        // check for map options
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
        var mapElement = document.getElementById('leaflet-map');
        if (mapElement !== null) {
            this.map = L.map('leaflet-map', {}).setView(this.mapCenter, this.zoomLevel);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(this.map);
            //var marker = L.marker([30.274271, -97.740317]).addTo(this.map);
            document.addEventListener("DOMContentLoaded", this.addMarkers.bind(this));
        }
    }

    addMarker(latitude, longitude) {
        var marker = L.marker([latitude, longitude]).addTo(this.map);
    }

    addMarkers() {
        /*
        var cluster = L.markerClusterGroup({
            showCoverageOnHover: false,
            zoomToBoundsOnClick: false,
            spiderfyOnMaxZoom: true,
            removeOutsideVisibleBounds: true,
            maxClusterRadius: 40,
            spiderfyPolylineOptions: { weight: 1.5, color: '#442A88', opacity: 0.5 }, // FCECAC FFE77F EE7E7E EB4C67 B1A6CE 442A88
            iconCreateFunction: this.iconCreationFunction
        });
        */
        var coordinateElements = document.getElementsByClassName('coordinate-data');
        //var eventLinks = document.getElementsByClassName('event-url');
        //var markers = [];
        for (var i = 0; i < coordinateElements.length; i++) {
            var latitude = coordinateElements[i].getAttribute('latitude');
            var longitude = coordinateElements[i].getAttribute('longitude');
            //var eventName = coordinateElements[i].getAttribute('eventName');
            var coordinateTitle = coordinateElements[i].getAttribute('coordinateTitle');
            var reviewCount = coordinateElements[i].getAttribute('reviewCount');
            var coordinateLinkUrl = coordinateElements[i].getAttribute('coordinateLinkUrl');
            //var eventDateTime = coordinateElements[i].getAttribute('eventDateTime');
            //var venueImageUrl = coordinateElements[i].getAttribute('venueImageUrl');
            //var eventUrl = "";
            //if (eventLinks.length > i) { eventUrl = eventLinks[i].getAttribute('href'); }

            // Build marker
            var marker = L.marker([latitude, longitude], {
                icon: new L.DivIcon({ html: '<div class="jm-map-icon-dot"></div>', className: 'jm-map-icon', iconSize: new L.Point(30, 30) }),
                title: 'marker',//venueName,
                //alt: "map marker for " + venueName
            })

            // Build Popup
            if (this.enablePopups) {
                var popupContent = `
                    <div style="margin: 0; width: 271px;">
                        <a class="map-popup-image-container" href="${coordinateLinkUrl}">
                            <div class="map-popup-headings">
                                <h3>${coordinateTitle}</h3>
                            </div>
                        </a>
                        <div class="map-popup-date-time">${reviewCount} reviews</div>
                    </div>
                `;
                var popup = L.popup()
                    .setLatLng([latitude, longitude])
                    .setContent(popupContent)

                marker.bindPopup(popup);
            }

            //markers.push(marker);
            marker.addTo(this.map);
        }
        //cluster.addLayers(markers);
        //this.map.addLayer(cluster);
    }

    iconCreationFunction(cluster) {
        var childCount = cluster.getChildCount();
        return new L.DivIcon({ html: '<div><span >' + childCount + '</span></div>', className: 'jm-map-cluster-icon', iconSize: new L.Point(40, 40) });
    }
}

export default LeafletMap
