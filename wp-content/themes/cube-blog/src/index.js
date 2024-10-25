// Import modules
import LeafletMap from "./modules/LeafletMap";
import ChartGenerator from "./modules/ChartGenerator";
import VenuePageManager from "./modules/VenuePageManager";
import VenueArchiveManager from "./modules/VenueArchiveManager";
import VenueReviewFormManager from "./modules/VenueReviewFormManager";

// Instantiate
const leafletMap = new LeafletMap();
const chartGenerator = new ChartGenerator();
const venuePageManager = new VenuePageManager(chartGenerator);
const venueArchiveManager = new VenueArchiveManager();
const venueReviewFormManager = new VenueReviewFormManager();
