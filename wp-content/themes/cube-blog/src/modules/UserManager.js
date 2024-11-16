import axios from 'axios';

class UserManager {
    constructor() {
        this._setupElements();
        this._setupListeners();
    }
    _setupElements() {
        this.registrationFormElement = document.getElementById('registration-form');
        this.registrationSubmitElement = document.getElementById('register-submit');
        this.errorContainerElement = document.getElementById('error-container');
    }
    _setupListeners() {
        this.registrationSubmitElement.addEventListener('click', this.registerUser.bind(this));
    }

    registerUser(evnt) {
        evnt.preventDefault();
        this.clearErrors();
        return this.callRegisterUserAPI().then((response) => {
            window.location.href = siteData.root_url;
        }).catch( (err) => {
            this.displayErrors(err.response.data.data.errors);
        });
    }
    callRegisterUserAPI() {
        return axios.post(`${siteData.user_registration_api_url}`, this.registrationFormElement);
    }

    displayErrors(errors) {
        let errorHtml = ''
        for (let iterator = 0; iterator < errors.length; iterator++) {
            errorHtml += `<div class="error_message">${errors[iterator]}</div>`;
        }
        this.errorContainerElement.innerHTML = errorHtml;
    }
    clearErrors() {
        this.errorContainerElement.innerHTML = '';
    }
}

export default UserManager
