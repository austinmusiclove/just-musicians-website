import StarRatingInput from "./StarRatingInput";

class StarRatingInputFactory {
    constructor() {}
    create(starClass, starGroup) {
        return new StarRatingInput(starClass, starGroup);
    }
}

export default StarRatingInputFactory;
