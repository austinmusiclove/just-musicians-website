import TagInput from "./TagInput";

class YouTubeTagInput extends TagInput {
    constructor(inputName, textInput, tagContainer, tagClass, deleteClass, helper, youTubeHelper, svgLibrary, errorDisplay, dragSortListFactory) {
        super(inputName, textInput, tagContainer, tagClass, deleteClass, helper, svgLibrary, errorDisplay);
        this.youTubeHelper = youTubeHelper;
        this.dragSortListFactory = dragSortListFactory;
        this._setupComponents();
        this.draggedItem = null;
    }
    _setupListeners() {
        super._setupListeners();
        this.textInput.addEventListener('paste', this.handleTagInputPaste.bind(this));
    }
    _setupComponents() {
        this.dragSortList = this.dragSortListFactory.create(this.tagContainer, this.tagClass);
    }

    handleTagInputPaste(evnt) {
        evnt.preventDefault();
        let clipboardData = evnt.clipboardData || window.clipboardData;
        let pastedText = clipboardData.getData('text');
        this.textInput.value = pastedText;
        this.addTag(pastedText);
    }
    checkTagErrors(tagName, tagSlug) {
        if (tagName == '') { return 'Please enter a valid youtube video link'; }
        if (!this.helper.isValidUrl(tagName)) { return 'Invalid URL'; }
        if (!this.youTubeHelper.isYoutubeUrl(tagName)) { return 'Only links from youtube.com are accepted.' }
        if (!this.youTubeHelper.getVideoIdFromYoutubeUrl(tagName)) { return 'Invalid YouTube URL. No video id. Make sure this is a link to a video, not a channel or a short.' }
        return false;
    }
    addNewTag(tagName, tagSlug) {
        let newTag = document.createElement('div')
        newTag.classList.add(this.tagClass);
        newTag.setAttribute('draggable', true);
        let youtubeIframeId = `${tagSlug}-${this.helper.getRandomNumber(8)}`;
        newTag.innerHTML = `
            <div style="display:flex">
                <div style="display: flex; flex-direction: column; justify-content: center; padding: 10px;">
                    <div class="${this.deleteClass}">${this.svgLibrary.getRedXSvg()}</div>
                    <div>${this.svgLibrary.getDragPointSvg()}</div>
                </div>
                <div>
                    <label name="${this.inputName}[]">${tagName}</label>
                    <input checked style="display:none;" value="${tagName}" type="checkbox" name="${this.inputName}[]" id="${tagSlug}"/>
                    <div style="display:block; height:200px;" id="${youtubeIframeId}"></div>
                </div>
            </div>
        `;
        newTag.addEventListener('click', this.handleRemoveTag.bind(this));
        this.tagContainer.appendChild(newTag);
        this.youTubeHelper.addYoutubeIframe(youtubeIframeId, tagName);
    }
}

export default YouTubeTagInput;
