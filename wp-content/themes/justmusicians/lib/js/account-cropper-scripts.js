/*
 * Handles interfacing with cropper.js 1.6.2 on the account settings page
 *
 */

async function processNewImage(alco, submitButtons) {
    alco.imageProcessing = true;

    if (!alco.cropper) return;
    const croppedCanvas = alco.cropper.getCroppedCanvas();
    var imageData = null;
    var processingType = 'canvas';

    // Use a worker to convert using OffscreenCanvas
    if (browserSupportsWebPInCanvas()) {
        const ctx = croppedCanvas.getContext('2d');
        imageData = ctx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height);

    // Use worker with wasm encoding
    } else {
        imageData = croppedCanvas.getContext('2d').getImageData(0, 0, croppedCanvas.width, croppedCanvas.height);
        processingType = 'libwebp';
    }

    const worker = new Worker(`${siteData.templateDirectoryUri}/lib/js/workers/image-processing-worker.js`);
    alco.accountSettings.profile_image.worker = worker;
    worker.postMessage({
        type:                 processingType,
        imageData:            imageData.data.buffer,
        width:                croppedCanvas.width,
        height:               croppedCanvas.height,
        templateDirectoryUri: siteData.templateDirectoryUri,
    }, [imageData.data.buffer]);

    worker.onmessage = async function (e) {
        if (e.data.success) {
            await processBlobAsWebp(e.data.blob, alco);
        } else {
            alco.$dispatch('error-toast', { 'message': 'Failed to process image'});
        }
        alco.imageProcessing = false;
        alco.accountSettings.profile_image.worker = null;
        enableButtons(submitButtons);
    };

}

async function processBlobAsWebp(blob, alco) {
    if (blob) {
        var filename = `${alco.accountSettings.profile_image.filename.replace(/\.[^/.]+$/, '')}.webp`;
        var file = new File([blob], filename, { type: 'image/webp' });
        var croppedImageUrl = URL.createObjectURL(blob);
        await updateImage(alco, croppedImageUrl, file);
    }
}
function browserSupportsWebPInCanvas() {
    var canvas = document.createElement('canvas');
    return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
}

function handleCropEnd(alco) {
    if (alco.accountSettings.profile_image.worker) {
        alco.accountSettings.profile_image.worker.terminate();
    }
}
function handleCropperReady(alco, newFile) {
    alco.imageProcessing = false;
}

function initCropper(alco, displayElement, displaySrc, submitButtons, newFile) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.imageProcessing = true;

    // Destroy existing cropper if it exists
    if (alco.cropper) { alco.cropper.destroy(); }

    // Only init cropper after the display loads
    displayElement.onload = function () {

        // Destroy existing cropper if it exists
        if (alco.cropper) {
            alco.cropper.destroy();
            alco.cropper = null;
        } else {

            alco.cropper = new Cropper(displayElement, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                zoomable: 0,
                ready: function() { handleCropperReady(alco, newFile); },
                cropend: function() { handleCropEnd(alco); },
            });
        }
    };

    displayElement.src = displaySrc;

    // If it's already loaded (cached), fire manually
    if (displayElement.complete && displayElement.naturalWidth !== 0) {
        displayElement.onload();
    }

}
function initCropperFromFile(alco, event, displayElement, submitButtons) {

    var files = event.target.files;
    if (files && files.length > 0) {

        // Disable submit button until image processing is complete
        disableButtons(submitButtons);
        alco.imageProcessing = true;

        var newImageData = {
            'file':       files[0],
            'filename':   files[0].name,
            'url':        '',
            'worker':     null,
            'is_default': false,
        };
        alco.accountSettings.profile_image = newImageData;

        var reader = new FileReader();
        reader.onload = function (evnt) {
            initCropper(alco, displayElement, evnt.target.result, submitButtons, true);
        };

        reader.readAsDataURL(files[0]);
    }
}
async function closeCropper(alco, submitButtons) {
    await processNewImage(alco, submitButtons);
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

function updateImage(alco, url, file) {
    alco.accountSettings.profile_image.url = url;
    alco.accountSettings.profile_image.file = file;
    alco.accountSettings.profile_image.attachment_id = '';

    // Put file in file input
    if (alco.accountSettings.profile_image.file) {
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(alco.accountSettings.profile_image.file);
        alco.$refs.profile_image_file.files = dataTransfer.files;
    }
}
