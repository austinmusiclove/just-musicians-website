class PhoneInput {
    constructor(inputElement) {
        this.inputElement = inputElement;
        this._setupListeners();
    }
    _setupListeners() {
        this.inputElement.addEventListener('input', this.reformat.bind(this));
    }
    reformat(evnt) {
        let match = evnt.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        evnt.target.value = !match[2] ? match[1] : `(${match[1]}) ${match[2]}` + (match[3] ? `-${match[3]}` : '');
    }
}

export default PhoneInput;

