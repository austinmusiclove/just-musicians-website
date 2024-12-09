class TextInputOptionDropdown {
    constructor(textInput, optionDropdown, fetchFunction, dataIdField, dataNameField, optionSelectionCallback, helper) {
        this.textInput = textInput;
        this.optionDropdown = optionDropdown;
        this.fetchFunction = fetchFunction;
        this.dataIdField = dataIdField;
        this.dataNameField = dataNameField;
        this.optionSelectionCallback = optionSelectionCallback;
        this.helper = helper;
        this.optionsMap = {};

        this._setupListeners();
        this.loadOptions();
    }
    _setupListeners() {
        this.textInput.addEventListener('keyup', this.handleTextInputChange.bind(this));
    }

    handleTextInputChange(evnt) {
        let inputValue = evnt.target.value.toLowerCase();
        if (inputValue.length > 1) {
            this.helper.show(this.optionDropdown);
            for (const [optionName, optionElement] of Object.entries(this.optionsMap)) {
                if (optionName.toLowerCase().includes(inputValue)) {
                    this.helper.show(optionElement);
                } else {
                    this.helper.hide(optionElement);
                }
            }
        } else {
            this.helper.hide(this.optionDropdown);
        }
    }
    handleOptionClick(evnt) {
        let id = evnt.target.getAttribute('data-id');
        let name = evnt.target.getAttribute('data-name');
        this.textInput.value = name;
        this.optionSelectionCallback(id, name);
        this.helper.hide(this.optionDropdown);
    }
    loadOptions() {
        this.fetchFunction().then( (response) => {
            return response.data;
        }).then((data) => {
            let html = '';
            for (let iterator = 0; iterator < data.length; iterator++) {
                let identifier = data[iterator][this.dataIdField];
                let name = data[iterator][this.dataNameField];
                let optionElement = document.createElement('div');
                optionElement.setAttribute('id', `option-${identifier}`);
                optionElement.setAttribute('data-id', identifier);
                optionElement.setAttribute('data-name', name);
                optionElement.innerText = name;
                optionElement.addEventListener('click', this.handleOptionClick.bind(this));
                this.optionDropdown.appendChild(optionElement);
                this.optionsMap[name] = optionElement;
            }
        }).catch( (err) => {
            console.warn(err);
        });
    }
}

export default TextInputOptionDropdown;
