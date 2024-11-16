import axios from 'axios';

class VenueReviewFormManager {
    // TODO move all ids to the php page and have them sent in with an event
    // TODO add unit tests
    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupElements();
            this.setupEventListeners();
            this.loadVenueOptions();
            this.preFill();
        });
    }

    setupElements() {
        this.venueInput = document.getElementById('venue_name');
        this.venueIdInput = document.getElementById('venue_id');
        this.venueOptions = document.getElementById('venue_options');
        this.venueOptionsMap = {};
        this.performanceIdInput = document.getElementById('performance_id');
        this.performancePostIdInput = document.getElementById('performance_post_id');
        this.performerInput = document.getElementById('performing_act_name');
        this.performanceDateInput = document.getElementById('performance_date');
        this.stars = document.querySelectorAll('.star');
        this.versusToggle = document.getElementById("has_versus_comp");
        this.versusInput1 = document.getElementById("versus_comp_1");
        this.versusInput2 = document.getElementById("versus_comp_2");
        this.guaranteeQuestions = document.getElementById("guarantee_questions");
        this.doorQuestions = document.getElementById("door_questions");
        this.barQuestions = document.getElementById("bar_questions");
    }
    setupEventListeners() {
        // Handle Venue input
        this.venueInput.addEventListener('keyup', this.handleVenueInputChange.bind(this));
        // Handle rating input elements
        this.stars.forEach(star => {
            star.addEventListener('mouseover', this.handleStarMouseover.bind(this));
            star.addEventListener('mouseout', this.handleStarMouseout.bind(this));
            star.addEventListener('click', this.handleStarClick.bind(this));
        });
        // Handle versus input
        this.versusToggle.addEventListener('change', this.handleVersusToggle.bind(this));
        this.versusInput1.addEventListener('change', this.handleVersusOptionChange.bind(this));
        this.versusInput2.addEventListener('change', this.handleVersusOptionChange.bind(this));
    }

    // Handle Venue input field
    handleVenueInputChange(evnt) {
        let inputValue = evnt.target.value.toLowerCase();
        if (inputValue.length > 1) {
            this.show(this.venueOptions);
            for (const [venueName, venueOptionElement] of Object.entries(this.venueOptionsMap)) {
                if (venueName.toLowerCase().includes(inputValue)) {
                    this.show(venueOptionElement);
                } else {
                    this.hide(venueOptionElement);
                }
            }
        } else {
            this.hide(this.venueOptions);
        }
    }
    handleVenueOptionClick(evnt) {
        this.venueInput.value = evnt.target.getAttribute('data-name');
        this.venueIdInput.value = evnt.target.getAttribute('data-id');
        this.hide(this.venueOptions);
    }
    loadVenueOptions() {
        this.getVenues().then( (response) => {
            return response.data;
        }).then((data) => {
            let html = '';
            for (let iterator = 0; iterator < data.length; iterator++) {
                html += this.getVenueOptionHtml(data[iterator]);
            }
            this.venueOptions.innerHTML = html;
            for (let iterator = 0; iterator < data.length; iterator++) {
                let venue = data[iterator];
                let optionElement = document.getElementById(`venue-${venue['ID']}`);
                this.venueOptionsMap[venue['name']] = optionElement;
                optionElement.addEventListener('click', this.handleVenueOptionClick.bind(this));
            }
        }).catch( (err) => {
            console.warn(err);
        });
    }
    getVenueOptionHtml(venue) {
        return `<div id="venue-${venue.ID}" data-name="${venue.name}" data-id="${venue.ID}">${venue.name}</div>`;
    }
    getVenues() {
        return axios.get(`${siteData.venues_api_url}/?min_review_count=0`);
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

    // Rating inputs
    handleStarMouseover(evnt) {
        const inputGroup = evnt.target.getAttribute('group');
        const ratingValue = evnt.target.getAttribute('value');
        this.highlightStars(inputGroup, ratingValue);
    }
    handleStarMouseout(evnt) {
        const inputGroup = evnt.target.getAttribute('group');
        const selectedRating = document.querySelector(`input[name="${inputGroup}"]:checked`);
        this.highlightStars(inputGroup, selectedRating ? selectedRating.value : 0);
    }
    handleStarClick(evnt) {
        const inputGroup = evnt.target.getAttribute('group');
        const ratingValue = evnt.target.getAttribute('value');
        evnt.target.checked = true;
        this.highlightStars(inputGroup, ratingValue);
    }
    highlightStars(inputGroup, rating) {
        const inputGroupStars = document.querySelectorAll(`.star[group=${inputGroup}]`);
        inputGroupStars.forEach(star => {
            if (star.getAttribute('value') <= rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }

    // Versus input
    handleVersusToggle(evnt) {
        if (!evnt.target.checked) {
            this.versusInput1.selectedIndex = 0;
            this.versusInput2.selectedIndex = 0;
            this.handleVersusOptionChange();
        }
    }
    handleVersusOptionChange() {
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
        // disallow having the same option on both sides
    }

    show(element) {
        element.style.display = "block";
    }
    hide(element) {
        element.style.display = "none";
    }
}

export default VenueReviewFormManager
