/*
 * Handles interfacing with cropper.js 1.6.2 on the account settings page
 *
 */
function handleCropEnd(alco, displayElement, submitButtons, isCropEnd) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.imageProcessing = true;

    if (alco.cropper) {
        var croppedCanvas = alco.cropper.getCroppedCanvas();

        croppedCanvas.toBlob((blob) => {
            if (blob) {
                var filename = `${alco.accountSettings.profile_image.filename}.replace(/\.[^/.]+$/, '')}.webp`;
                var file = new File([blob], filename, { type: 'image/webp' });
                var croppedImageUrl = URL.createObjectURL(blob);
                updateImage(alco, croppedImageUrl, file, isCropEnd);

                // Enable submit button
                enableButtons(submitButtons);
                alco.imageProcessing = false;
            }
        }, 'image/webp');
    }
}
function initCropper(alco, displayElement, submitButtons) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.imageProcessing = true;

    // Destroy existing cropper if it exists
    if (alco.cropper) { alco.cropper.destroy(); }

    alco.cropper = new Cropper(displayElement, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 1,
        zoomable: 0,
        ready: function() { handleCropEnd(alco, displayElement, submitButtons, false) },
        cropend: function() { handleCropEnd(alco, displayElement, submitButtons, true) },
    });
}
function initCropperFromFile(alco, event, displayElement, submitButtons) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.imageProcessing = true;

    var files = event.target.files;

    if (files && files.length > 0) {
        var newImageData = {
            'file':      files[0],
            'filename':  files[0].name,
            'url':       '',
        };
        alco.accountSettings.profile_image = newImageData;

        var reader = new FileReader();
        reader.onload = function (evnt) {
            displayElement.src = evnt.target.result;
            initCropper(alco, displayElement, submitButtons);
        };

        reader.readAsDataURL(files[0]);
    }
}
function closeCropper(alco) {
    if (alco.cropper) { alco.cropper.destroy(); alco.cropper = null;}
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

function updateImage(alco, url, file, wasCropped) {
    alco.accountSettings.profile_image.url = url;
    alco.accountSettings.profile_image.file = file;
    if (wasCropped) { alco.accountSettings.profile_image.attachment_id = ''; }

    // Put file in file input
    if (alco.accountSettings.profile_image.file) {
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(alco.accountSettings.profile_image.file);
        alco.$refs.profile_image_file.files = dataTransfer.files;
    }
}
