import axios from 'axios';

class VenueReviewFormManager {
    constructor(helper, textInputOptionDropdownFactory, starRatingInputFactory) {
        this.helper = helper;
        this.textInputOptionDropdownFactory = textInputOptionDropdownFactory;
        this.starRatingInputFactory = starRatingInputFactory;
        document.addEventListener('DOMContentLoaded', () => {
            this._setupElements();
            this._setupEventListeners();
            this._setupComponents();
            this.preFill();
        });
    }
    _setupElements() {
        this.venueInput = document.getElementById('venue-name');
        this.venueIdInput = document.getElementById('venue-id');
        this.venueOptions = document.getElementById('venue-options');
        this.performanceIdInput = document.getElementById('performance-id');
        this.performancePostIdInput = document.getElementById('performance-post-id');
        this.performerInput = document.getElementById('performing-act-name');
        this.performanceDateInput = document.getElementById('performance-date');
        this.versusToggle = document.getElementById("has-versus-comp");
        this.versusInput1 = document.getElementById("versus-comp-1");
        this.versusInput2 = document.getElementById("versus-comp-2");
        this.guaranteeQuestions = document.getElementById("guarantee-questions");
        this.doorQuestions = document.getElementById("door-questions");
        this.barQuestions = document.getElementById("bar-questions");
    }
    _setupEventListeners() {
        this.versusToggle.addEventListener('change', this.handleVersusToggle.bind(this));
        this.versusInput1.addEventListener('change', this.handleVersusOptionChange.bind(this));
        this.versusInput2.addEventListener('change', this.handleVersusOptionChange.bind(this));
    }
    _setupComponents() {
        function getVenues() { return axios.get(`${siteData.venues_api_url}/?min_review_count=0`); }
        function setVenueId(venueId, venueName) { this.venueIdInput.value = venueId; }
        this.venueInputOptionDropdown = this.textInputOptionDropdownFactory.create(this.venueInput, this.venueOptions, getVenues, 'ID', 'name', setVenueId.bind(this));
        this.overallRatingInput = this.starRatingInputFactory.create('star', 'overall-rating');
        this.communicationRatingInput = this.starRatingInputFactory.create('star', 'communication-rating');
        this.soundRatingInput = this.starRatingInputFactory.create('star', 'sound-rating');
        this.safetyRatingInput = this.starRatingInputFactory.create('star', 'safety-rating');
    }

    // Versus input
    handleVersusToggle(evnt) {
        if (!evnt.target.checked) {
            this.versusInput1.selectedIndex = 0;
            this.versusInput2.selectedIndex = 0;
            this.resetVersusOptions(); // enable all other options
            this.showAppropriateVersusQuestions();
        }
    }
    handleVersusOptionChange(evnt) {
        this.resetVersusOptions(evnt.target.value);  // disallows having the same option on both sides
        this.showAppropriateVersusQuestions();
    }
    resetVersusOptions(option) {
        this.disableMatch([...this.versusInput1.children].slice(1), option);
        this.disableMatch([...this.versusInput2.children].slice(1), option);
    }
    disableMatch(elements, match) {
        for (let iterator = 0; iterator < elements.length; iterator++) {
            let optionElement = elements[iterator];
            optionElement.disabled = optionElement.value == match;
        }
    }
    showAppropriateVersusQuestions() {
        let versus1 = this.versusInput1.value;
        let versus2 = this.versusInput2.value;
        if (versus1 == 'Guarantee' || versus2 == 'Guarantee') {
            this.guaranteeQuestions.classList.add('versus-option');
        } else {
            this.guaranteeQuestions.classList.remove('versus-option');
        }
        if (versus1 == 'Door Deal' || versus2 == 'Door Deal') {
            this.doorQuestions.classList.add('versus-option');
        } else {
            this.doorQuestions.classList.remove('versus-option');
        }
        if (versus1 == 'Bar Deal' || versus2 == 'Bar Deal') {
            this.barQuestions.classList.add('versus-option');
        } else {
            this.barQuestions.classList.remove('versus-option');
        }
    }

    preFill() {
        let performanceId = this.performanceIdInput.value;
        this.getPerformance(performanceId).then( (response) => {
            return response.data;
        }).then((data) => {
            if (data) {
                this.performancePostIdInput.value = data.id;
                this.venueInput.value = data.venue_name;
                this.venueIdInput.value = data.venue;
                this.performerInput.value = data.performing_act_name;
                this.performanceDateInput.value = data.performance_date;
            }
        }).catch( (err) => {
            console.warn(err);
        });
    }
    getPerformance(performanceId) {
        return axios.get(`${siteData.performance_by_id_api_url}/?performance_id=${performanceId}`);
    }
}

export default VenueReviewFormManager
