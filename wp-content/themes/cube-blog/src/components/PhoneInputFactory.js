import PhoneInput from "./PhoneInput";

class PhoneInputFactory {
    constructor() { }
    create(inputElement) {
        return new PhoneInput(inputElement);
    }
}

export default PhoneInputFactory;

