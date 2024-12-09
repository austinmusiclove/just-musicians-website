import axios from 'axios';

class SignUpManager {
    constructor(errorDisplayFactory) {
        this.errorDisplayFactory = errorDisplayFactory;
        document.addEventListener('DOMContentLoaded', () => {
            this._setupElements();
            this._setupListeners();
            this._setupComponents();
        });
    }
    _setupElements() {
        this.registrationFormElement = document.getElementById('registration-form');
        this.registrationSubmitElement = document.getElementById('register-submit');
    }
    _setupListeners() {
        this.registrationSubmitElement.addEventListener('click', this.registerUser.bind(this));
    }
    _setupComponents() {
        this.errorDisplay = this.errorDisplayFactory.create('error-container', 'error-message');
    }

    registerUser(evnt) {
        evnt.preventDefault();
        this.errorDisplay.clearErrors();
        return this.callRegisterUserAPI().then((response) => {
            window.location.href = siteData.root_url;
        }).catch( (err) => {
            console.log(err.response.data.data.errors);
            this.errorDisplay.showErrors(err.response.data.data.errors);
        });
    }
    callRegisterUserAPI() {
        return axios.post(`${siteData.user_registration_api_url}`, this.registrationFormElement);
    }
}

export default SignUpManager;
