import CroppableImageInput from "./CroppableImageInput";

class CroppableImageInputFactory {
    constructor() { }
    create(imageInput, croppedImageInput, imageDisplay) {
        return new CroppableImageInput(imageInput, croppedImageInput, imageDisplay);
    }
}

export default CroppableImageInputFactory;

