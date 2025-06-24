/*
 * Handles interfacing with cropper.js 1.6.2
 *
 */
function handleCropEnd(alco, displayElement, imageType, imageId, submitButtons, isCropEnd) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;

    if (alco.cropper) {
        var croppedCanvas = alco.cropper.getCroppedCanvas();

        croppedCanvas.toBlob((blob) => {
            if (blob) {
                var filename = `${alco.imageData[imageType][imageId]['filename'].replace(/\.[^/.]+$/, '')}.webp`;
                var file = new File([blob], filename, { type: 'image/webp' });
                var croppedImageUrl = URL.createObjectURL(blob);
                alco._updateImage(imageType, imageId, croppedImageUrl, file, isCropEnd);

                // Enable submit button
                enableButtons(submitButtons);
                alco.showImageProcessingSpinner = false;
            }
        }, 'image/webp');
    }
}
function initCropper(alco, displayElement, imageType, imageId, submitButtons) {
    alco.currentImageId = imageId;

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;

    // Destroy existing cropper if it exists
    if (alco.cropper) { alco.cropper.destroy(); }

    alco.cropper = new Cropper(displayElement, {
        aspectRatio: 4 / 3,
        viewMode: 1,
        autoCropArea: 1,
        zoomable: 0,
        ready: function() { handleCropEnd(alco, displayElement, imageType, imageId, submitButtons, false) },
        cropend: function() { handleCropEnd(alco, displayElement, imageType, imageId, submitButtons, true) },
    });
}
function initCropperFromFile(alco, event, displayElement, imageType, imageId, submitButtons) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;

    var files = event.target.files;

    if (files && files.length > 0) {
        if (!imageId) {
            imageId = generateRandomId();
        }
        var newImageData = {
            'file':      files[0],
            'filename':  files[0].name,
            'url':       '',
            'caption':   '',
            'mediatags': [],
        };
        alco._addImage(imageType, imageId, newImageData);

        var reader = new FileReader();
        reader.onload = function (evnt) {
            displayElement.src = evnt.target.result;
            initCropper(alco, displayElement, imageType, imageId, submitButtons);
        };

        reader.readAsDataURL(files[0]);
    }
}

function disableButtons(buttons) {
    if (!Array.isArray(buttons)) return;
    buttons.forEach(button => {
        if (button instanceof HTMLElement && typeof button.disabled !== 'undefined') {
            button.disabled = true;
        }
    });
}
function enableButtons(buttons) {
    if (!Array.isArray(buttons)) return;
    buttons.forEach(button => {
        if (button instanceof HTMLElement && typeof button.disabled !== 'undefined') {
            button.disabled = false;
        }
    });
}

function generateRandomId() {
    const array = new Uint8Array(16);
    window.crypto.getRandomValues(array);
    return Array.from(array, b => b.toString(16).padStart(2, '0')).join('');
}
