class TagInput {
    constructor(inputName, textInput, tagContainer, tagClass, deleteClass, helper, svgLibrary, errorDisplay) {
        this.inputName = inputName
        this.textInput = textInput;
        this.tagContainer = tagContainer;
        this.tagClass = tagClass;
        this.deleteClass = deleteClass;
        this.helper = helper;
        this.svgLibrary = svgLibrary;
        this.errorDisplay = errorDisplay;
        this._setupListeners();
    }
    _setupListeners() {
        this.textInput.addEventListener('keydown', this.handleTagInputKeyDown.bind(this));
    }
    handleTagInputKeyDown(evnt) {
        if (evnt.key === 'Enter') {
            evnt.preventDefault();
            this.addTag();
        }
    }
    addTag(tagToAdd) {
        this.errorDisplay.clearErrors();
        let tagName = (tagToAdd) ? tagToAdd : this.textInput.value;
        let tagSlug = this.helper.convertStringToSlug(tagName);
        let error = this.checkTagErrors(tagName, tagSlug);
        if (!error) {
            this.addNewTag(tagName, tagSlug);
            this.textInput.value = '';
        } else {
            this.errorDisplay.showError(error);
        }
    }
    checkTagErrors(tagName, tagSlug) {
        if (tagSlug == '') { return 'Tag must contain at least one alphanumeric character'; }
        return false;
    }
    addNewTag(tagName, tagSlug) {
        let newTag = document.createElement('div')
        newTag.classList.add(this.tagClass);
        newTag.innerHTML = `
            <input checked value="${tagName}" type="checkbox" name="${this.inputName}[]" id="${tagSlug}"/>
            <label for="${tagSlug}">${tagName}</label>
            <div class="${this.deleteClass}">
            ${this.svgLibrary.getRedXSvg()}
            </div>
        `;
        newTag.addEventListener('click', this.handleRemoveTag.bind(this));
        this.tagContainer.appendChild(newTag);
    }
    handleRemoveTag(evnt) {
        if (evnt.target.closest(`.${this.deleteClass}`)) {
            evnt.target.closest(`.${this.tagClass}`).remove();
        }
    }
}

export default TagInput;
