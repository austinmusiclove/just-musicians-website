// Import modules
// Modules are built specifically for a page or set of pages
// Modules can contain references to HTML ids that are hard coded in the page
import ChartGenerator from "./modules/ChartGenerator";
import VenuePageManager from "./modules/VenuePageManager";
import VenueArchiveManager from "./modules/VenueArchiveManager";
import VenueReviewFormManager from "./modules/VenueReviewFormManager";
import ListingFormManager from "./modules/ListingFormManager";
import VenueInsightGenerator from "./modules/VenueInsightGenerator";
import SignUpManager from "./modules/SignUpManager";

// move to components
import LeafletMap from "./modules/LeafletMap"; // split between archive map and single point map

// Import Component Factories
// Components should not reference any hard coded HTML ids, instead
// Component Factory create methods should accept ids
// Modules should wait until after DOMContentLoaded to run create methods from component factories to avoid searching the DOM before it is loaded
import ErrorDisplayFactory from "./components/ErrorDisplayFactory";
import TagInputFactory from "./components/TagInputFactory";
import DragSortListFactory from "./components/DragSortListFactory";
import TextInputOptionDropdownFactory from "./components/TextInputOptionDropdownFactory";
import StarRatingInputFactory from "./components/StarRatingInputFactory";
import PhoneInputFactory from "./components/PhoneInputFactory";

// Import Libraries
// Libraries contain helper functions and reusable HTML snippets
import Helper from "./libraries/Helper.js";
import YouTubeHelper from "./libraries/YouTubeHelper.js";
import SvgLibrary from "./libraries/SvgLibrary.js";


// ************************************************************* //
// Only instantiate modules that are needed for the current page //
// ************************************************************* //

// Listing Form
if (siteData.url_path.includes('listing-form') && siteData.request_method == 'GET') {
    const helper = new Helper();
    const youTubeHelper = new YouTubeHelper(helper);
    const svgLibrary = new SvgLibrary();
    const errorDisplayFactory = new ErrorDisplayFactory(helper);
    const dragSortListFactory = new DragSortListFactory();
    const tagInputFactory = new TagInputFactory(helper, youTubeHelper, svgLibrary, errorDisplayFactory, dragSortListFactory);
    const textInputOptionDropdownFactory = new TextInputOptionDropdownFactory(helper);
    const phoneInputFactory = new PhoneInputFactory();
    const listingFormManager = new ListingFormManager(helper, tagInputFactory, textInputOptionDropdownFactory, phoneInputFactory);
}

// Venue Review Form
else if (siteData.url_path.includes('venue-review-form') && siteData.request_method == 'GET') {
    const helper = new Helper();
    const textInputOptionDropdownFactory = new TextInputOptionDropdownFactory(helper);
    const starRatingInputFactory = new StarRatingInputFactory();
    const venueReviewFormManager = new VenueReviewFormManager(helper, textInputOptionDropdownFactory, starRatingInputFactory);
}

// Venues archive and pages
else if (siteData.url_path.includes('venues')) {
    const leafletMap = new LeafletMap();
    const chartGenerator = new ChartGenerator();
    const venueInsightGenerator = new VenueInsightGenerator();
    const venuePageManager = new VenuePageManager(chartGenerator);
    const venueArchiveManager = new VenueArchiveManager(venueInsightGenerator);
}

// User Registration
else if (siteData.url_path.includes('sign-up')) {
    const helper = new Helper();
    const errorDisplayFactory = new ErrorDisplayFactory(helper);
    const signUpManager = new SignUpManager(errorDisplayFactory);
}
