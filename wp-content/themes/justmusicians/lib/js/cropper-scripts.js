/*
 * Handles interfacing with cropper.js 1.6.2
 *
 */
function handleCropEnd(alpineComponent, displayElement, croppedImageInput) {
    if (alpineComponent.cropper) {
        var croppedCanvas = alpineComponent.cropper.getCroppedCanvas();

        croppedCanvas.toBlob((blob) => {
            if (blob) {
                var file = new File([blob], 'cropped-image.webp', { type: 'image/webp' });
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                croppedImageInput.files = dataTransfer.files;

                const croppedImageUrl = URL.createObjectURL(blob);
                alpineComponent.$dispatch('updatethumbnail', croppedImageUrl);
            }
        }, 'image/webp');
    }
}
function initCropper(alpineComponent, displayElement, croppedImageInput) {
    // Destroy existing cropper if it exists
    if (alpineComponent.cropper) { alpineComponent.cropper.destroy(); }

    alpineComponent.cropper = new Cropper(displayElement, {
        aspectRatio: 4 / 3,
        viewMode: 1,
        autoCropArea: 1,
        zoomable: 0,
        ready: function() { handleCropEnd(alpineComponent, displayElement, croppedImageInput) },
        cropend: function() { handleCropEnd(alpineComponent, displayElement, croppedImageInput) },
    });
}
function initCropperFromFile(alpineComponent, event, displayElement, croppedImageInput) {
    var files = event.target.files;
    if (files && files.length > 0) {
        var reader = new FileReader();
        reader.onload = function (evnt) {
            displayElement.src = evnt.target.result;
            initCropper(alpineComponent, displayElement, croppedImageInput);
        };

        reader.readAsDataURL(files[0]);
    }
}
