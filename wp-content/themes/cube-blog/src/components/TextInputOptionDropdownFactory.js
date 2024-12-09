import TextInputOptionDropdown from "./TextInputOptionDropdown";

class TextInputOptionDropdownFactory {
    constructor(helper) {
        this.helper = helper;
    }
    create(textInput, optionDropdown, fetchFunction, dataIdField, dataNameField, optionSelectionCallback) {
        return new TextInputOptionDropdown(textInput, optionDropdown, fetchFunction, dataIdField, dataNameField, optionSelectionCallback, this.helper);
    }
}

export default TextInputOptionDropdownFactory;
