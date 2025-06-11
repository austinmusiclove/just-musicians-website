/*
 * Handles interfacing with cropper.js 1.6.2
 *
 */
// TODO disable multiple submit buttons
function handleCropEnd(alpineComponent, displayElement, croppedImageInput, submitButton) {

    // Disable submit button until image processing is complete
    submitButton.disabled = true;
    alpineComponent.showImageProcessingSpinner = true;

    if (alpineComponent.cropper) {
        var croppedCanvas = alpineComponent.cropper.getCroppedCanvas();

        croppedCanvas.toBlob((blob) => {
            if (blob) {
                var file = new File([blob], `${alpineComponent.getFilenamePrefix()}.webp`, { type: 'image/webp' });
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                croppedImageInput.files = dataTransfer.files;

                var croppedImageUrl = URL.createObjectURL(blob);
                alpineComponent.$dispatch('updatethumbnail', croppedImageUrl);

                // Enable submit button
                submitButton.disabled = false;
                alpineComponent.showImageProcessingSpinner = false;
            }
        }, 'image/webp');
    }
}
function initCropper(alpineComponent, displayElement, croppedImageInput, submitButton) {

    // Disable submit button until image processing is complete
    submitButton.disabled = true;
    alpineComponent.showImageProcessingSpinner = true;

    // Destroy existing cropper if it exists
    if (alpineComponent.cropper) { alpineComponent.cropper.destroy(); }

    alpineComponent.cropper = new Cropper(displayElement, {
        aspectRatio: 4 / 3,
        viewMode: 1,
        autoCropArea: 1,
        zoomable: 0,
        ready: function() { handleCropEnd(alpineComponent, displayElement, croppedImageInput, submitButton) },
        cropend: function() { handleCropEnd(alpineComponent, displayElement, croppedImageInput, submitButton) },
    });
    // disable submit
}
function initCropperFromFile(alpineComponent, event, displayElement, croppedImageInput, submitButton) {

    // Disable submit button until image processing is complete
    submitButton.disabled = true;
    alpineComponent.showImageProcessingSpinner = true;

    var files = event.target.files;
    if (files && files.length > 0) {
        var reader = new FileReader();
        reader.onload = function (evnt) {
            displayElement.src = evnt.target.result;
            initCropper(alpineComponent, displayElement, croppedImageInput, submitButton);
        };

        reader.readAsDataURL(files[0]);
    }
}
