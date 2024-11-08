// Import modules
import LeafletMap from "./modules/LeafletMap";
import ChartGenerator from "./modules/ChartGenerator";
import VenuePageManager from "./modules/VenuePageManager";
import VenueArchiveManager from "./modules/VenueArchiveManager";
import VenueReviewFormManager from "./modules/VenueReviewFormManager";
import VenueInsightGenerator from "./modules/VenueInsightGenerator";

// Instantiate
const leafletMap = new LeafletMap();
const chartGenerator = new ChartGenerator();
const venueInsightGenerator = new VenueInsightGenerator();
const venuePageManager = new VenuePageManager(chartGenerator);
const venueArchiveManager = new VenueArchiveManager(venueInsightGenerator);
const venueReviewFormManager = new VenueReviewFormManager();
