// Keep GeoJSON source name constant
const SOURCE_ID = "venues";
const siteUrl = siteData.siteUrl;


// Init map
var map = new maplibregl.Map({
    container: 'map', // container id
    style: '/wp-content/themes/justmusicians/lib/maps/osm_liberty.json', // style URL
    center: [ -97.7431, 30.2672 ], // starting position [lng, lat]
    zoom: 12, // starting zoom
});

// Add zoom buttons
map.addControl(new maplibregl.NavigationControl({
    showCompass: false
}), 'top-right');

// Disable default zooming
map.scrollZoom.disable();

// Handle scroll event in the map
function handleMapWheelEvent(alco, event) {
    // CMD (Mac) / Windows key
    if (event.metaKey) {
        // Stop page scroll
        event.preventDefault();

        // Enable zoom while CMD is pressed
        enableScrollZoom();

        hideHint(alco);
    } else {
        // Disable zoom & show hint
        disableScrollZoom();
        showHint(alco);
    }
}

// Disable zoom when modifier key is released to avoid issue where second zoom does not work
window.addEventListener("keyup", (e) => {
    if (e.key === "Meta") {
        map.scrollZoom.disable();
    }
});

// Show overlay helper
function showHint(alco) {
    alco.showScrollHint = true;
    clearTimeout(window._overlayTimeout);
    window._overlayTimeout = setTimeout(() => {
        hideHint(alco);
    }, 3000);
}
function hideHint(alco) {
    alco.showScrollHint = false;
}

function enableScrollZoom() {
    if (!map.scrollZoom.isEnabled()) {
        map.scrollZoom.enable();
    }
}
function disableScrollZoom() {
    map.scrollZoom.disable();
}





// On load and moveend, get bounds and fetch venues
map.on('load', () => { updateMarkers(); });
map.on('moveend', () => { updateMarkers(); });


async function updateMarkers() {

    // Get venues
    const bounds = getVisibleBounds();
    const url = `${siteUrl}/wp-json/venues/v1/bounds?n=${bounds.north}&s=${bounds.south}&e=${bounds.east}&w=${bounds.west}`;
    const response = await fetch(url);
    const venues = await response.json();
    const geojson = venuesToGeoJSON(venues);

    // If source already exists → update it
    if (map.getSource(SOURCE_ID)) {
        map.getSource(SOURCE_ID).setData(geojson);
        return;
    }

    // Otherwise create clustered source + layers
    map.addSource(SOURCE_ID, {
        type: "geojson",
        data: geojson,
        cluster: true,
        clusterRadius: 50,
        clusterMaxZoom: 15
    });

    // Cluster circles
    map.addLayer({
        id: "clusters",
        type: "circle",
        source: SOURCE_ID,
        filter: ["has", "point_count"],
        paint: {
            "circle-color": "#d29429",
            "circle-radius": [
                "step",
                ["get", "point_count"],
                18, 10,
                25, 25,
                35
            ]
        }
    });

    // Cluster count number
    map.addLayer({
        id: "cluster-count",
        type: "symbol",
        source: SOURCE_ID,
        filter: ["has", "point_count"],
        layout: {
            "text-field": "{point_count_abbreviated}",
            "text-size": 12
        },
        paint: {
            "text-color": "#ffffff"
        }
    });

    // Individual venue markers
    map.addLayer({
        id: "unclustered-point",
        type: "circle",
        source: SOURCE_ID,
        filter: ["!", ["has", "point_count"]],
        paint: {
            "circle-color": "#846b4e",
            "circle-radius": 8,
            "circle-stroke-width": 2,
            "circle-stroke-color": "#846b4e"
        }
    });

    // Click on individual marker → show popup
    map.on("click", "unclustered-point", (e) => {
        const f = e.features[0];
        console.log(f);
        const props = f.properties;

        const html = `
            <h2 style="font-size: 16px; margin-bottom: 12px;"><a href="${props.permalink}" target="_blank">${props.name}</a></h2>
            <div style="display: flex; column-gap: 0.25rem; margin-bottom: 12px;">
                <div style="display: flex; column-gap: 0.25rem; color: #facc15; width: 8rem;">
                    ${getRatingStars(props.rating)}
                </div>
                <span style="font-size: 12px;">(${props.review_count} reviews)</span>
            </div>
            <p style="font-size: 14px; margin-bottom: 12px;">${props.address}</p>
        `;

        new maplibregl.Popup()
            .setLngLat(f.geometry.coordinates)
            .setHTML(html)
            .addTo(map);
    });

    // Click on cluster → zoom into it
    map.on("click", "clusters", (e) => {
        const features = map.queryRenderedFeatures(e.point, {
            layers: ["clusters"]
        });
        const clusterId = features[0].properties.cluster_id;

        map.getSource(SOURCE_ID).getClusterExpansionZoom(clusterId, (err, zoom) => {
            if (err) return;

            map.easeTo({
                center: features[0].geometry.coordinates,
                zoom: zoom
            });
        });
    });

    // Change cursor on hover
    map.on("mouseenter", "clusters", () => {
        map.getCanvas().style.cursor = "pointer";
    });
    map.on("mouseleave", "clusters", () => {
        map.getCanvas().style.cursor = "";
    });
}

function getVisibleBounds() {
    const b = map.getBounds();
    return {
        north: b.getNorth(),
        south: b.getSouth(),
        east: b.getEast(),
        west: b.getWest()
    };
}

/**
 * Convert WP venue objects → GeoJSON FeatureCollection
 */
function venuesToGeoJSON(venues) {
    console.log(venues);
    return {
        type: "FeatureCollection",
        features: venues.map(venue => ({
            type: "Feature",
            geometry: {
                type: "Point",
                coordinates: [parseFloat(venue.longitude), parseFloat(venue.latitude)]
            },
            properties: {
                id: venue.id,
                name: venue.name,
                permalink: venue.permalink,
                address: venue.address,
                rating: venue.rating,
                review_count: venue.review_count || '0',
            }
        }))
    };
}

function getRatingStars(rating) {
    return `
        ${getRatingStar(Math.min(100, Math.max(0, (rating - 0) * 100)))}
        ${getRatingStar(Math.min(100, Math.max(0, (rating - 1) * 100)))}
        ${getRatingStar(Math.min(100, Math.max(0, (rating - 2) * 100)))}
        ${getRatingStar(Math.min(100, Math.max(0, (rating - 3) * 100)))}
        ${getRatingStar(Math.min(100, Math.max(0, (rating - 4) * 100)))}
    `;
}

function getRatingStar(fillPercentage) {
    return `
        <svg viewBox="0 0 20 20" class="size-10" xmlns="http://www.w3.org/2000/svg">
          <!-- empty star (background) -->
          <path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                fill="white" stroke="currentColor"/>

          <!-- filled star (foreground), but clipped inline -->
          <g style="clip-path: inset(0 ${100 - fillPercentage}% 0 0);">
            <path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                  fill="currentColor" stroke="currentColor"/>
          </g>
        </svg>
    `
}
