import TagInput from "./TagInput";
import YouTubeTagInput from "./YouTubeTagInput";

class TagInputFactory {
    constructor(helper, youTubeHelper, svgLibrary, errorDisplayFactory, dragSortListFactory) {
        this.helper = helper;
        this.youTubeHelper = youTubeHelper;
        this.svgLibrary = svgLibrary;
        this.errorDisplayFactory = errorDisplayFactory;
        this.dragSortListFactory = dragSortListFactory;
    }
    create(inputName, textInput, tagContainer, tagClass, deleteClass, errorContainer, errorMessageClass, type) {
        let errorDisplay = this.errorDisplayFactory.create(errorContainer, errorMessageClass);

        if (type == 'youtube') {
            return new YouTubeTagInput(inputName, textInput, tagContainer, tagClass, deleteClass, this.helper, this.youTubeHelper, this.svgLibrary, errorDisplay, this.dragSortListFactory);
        }

        return new TagInput(inputName, textInput, tagContainer, tagClass, deleteClass, this.helper, this.svgLibrary, errorDisplay);
    }
}

export default TagInputFactory;
