class StarRatingInput {
    constructor(starClass, starGroup) {
        this.starClass = starClass
        this.starGroup = starGroup;
        document.addEventListener('DOMContentLoaded', () => {
            this._setupElements();
            this._setupListeners();
        });
    }
    _setupElements() {
        this.stars = document.querySelectorAll(`.${this.starClass}[group=${this.starGroup}]`);
    }
    _setupListeners() {
        this.stars.forEach(star => {
            star.addEventListener('mouseover', this.handleStarMouseover.bind(this));
            star.addEventListener('mouseout', this.handleStarMouseout.bind(this));
            star.addEventListener('click', this.handleStarClick.bind(this));
        });
    }
    handleStarMouseover(evnt) {
        const ratingValue = evnt.target.getAttribute('value');
        this.highlightStars(ratingValue);
    }
    handleStarMouseout(evnt) {
        const selectedRating = document.querySelector(`input[name="${this.starGroup}"]:checked`);
        this.highlightStars(selectedRating ? selectedRating.value : 0);
    }
    handleStarClick(evnt) {
        const ratingValue = evnt.target.getAttribute('value');
        evnt.target.checked = true;
        this.highlightStars(ratingValue);
    }
    highlightStars(rating) {
        this.stars.forEach(star => {
            if (star.getAttribute('value') <= rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }

}

export default StarRatingInput;
