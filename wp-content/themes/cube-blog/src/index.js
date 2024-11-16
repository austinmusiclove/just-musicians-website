// Import modules
import LeafletMap from "./modules/LeafletMap";
import ChartGenerator from "./modules/ChartGenerator";
import VenuePageManager from "./modules/VenuePageManager";
import VenueArchiveManager from "./modules/VenueArchiveManager";
import VenueReviewFormManager from "./modules/VenueReviewFormManager";
import VenueInsightGenerator from "./modules/VenueInsightGenerator";
import UserManager from "./modules/UserManager";

// Venue Review Form
if (siteData.url_path.includes('venue-review-form')) {
    const venueReviewFormManager = new VenueReviewFormManager();
}

// Venues archive and pages
if (siteData.url_path.includes('venues')) {
    const leafletMap = new LeafletMap();
    const chartGenerator = new ChartGenerator();
    const venueInsightGenerator = new VenueInsightGenerator();
    const venuePageManager = new VenuePageManager(chartGenerator);
    const venueArchiveManager = new VenueArchiveManager(venueInsightGenerator);
}

// User Registration
if (siteData.url_path.includes('sign-up')) {
    const userManager = new UserManager();
}
