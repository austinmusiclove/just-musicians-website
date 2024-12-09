import ErrorDisplay from "./ErrorDisplay";

class ErrorDisplayFactory {
    constructor(helper) {
        this.helper = helper;
    }
    create(errorContainer, errorMessageClass) {
        return new ErrorDisplay(this.helper, errorContainer, errorMessageClass);
    }
}

export default ErrorDisplayFactory;
