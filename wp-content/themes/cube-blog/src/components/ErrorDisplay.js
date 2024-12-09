class ErrorDisplay {
    constructor(helper, errorContainer, errorMessageClass) {
        this.helper = helper;
        this.errorContainer = errorContainer;
        this.errorMessageClass = errorMessageClass;
    }
    showErrors(errors) {
        for (let iterator = 0; iterator < errors.length; iterator++) {
            let newError = this.getErrorElement(errors[iterator]);
            this.errorContainer.appendChild(newError);
        }
        this.helper.show(this.errorContainer);
    }
    showError(errorString) {
        let newError = this.getErrorElement(errorString);
        this.errorContainer.appendChild(newError);
        this.helper.show(this.errorContainer);
    }
    getErrorElement(errorString) {
        let newError = document.createElement('div');
        newError.classList.add(this.errorMessageClass);
        newError.innerText = errorString;
        return newError;
    }
    clearErrors() {
        this.errorContainer.innerHTML = '';
        this.helper.hide(this.errorContainer);
    }
}

export default ErrorDisplay;
