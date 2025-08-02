/*
 * Handles interfacing with cropper.js 1.6.2
 *
 */
function processNewImage(alco, imageType, imageId, submitButtons) {
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;

    if (!alco.cropper) return;
    const croppedCanvas = alco.cropper.getCroppedCanvas();
    var imageData = null;
    var processingType = 'process-with-canvas';

    if (browserSupportsWebPInCanvas()) {
        // Use a worker to convert using OffscreenCanvas
        const ctx = croppedCanvas.getContext('2d');
        imageData = ctx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height);

    } else {
        // Use worker with wasm encoding
        imageData = croppedCanvas.getContext('2d').getImageData(0, 0, croppedCanvas.width, croppedCanvas.height);
        processingType = 'process-with-libwebp';
    }

    const worker = new Worker(`${siteData.templateDirectoryUri}/lib/js/workers/image-processing-worker.js`);
    worker.postMessage({
        type: processingType,
        imageData: imageData.data.buffer,
        width: croppedCanvas.width,
        height: croppedCanvas.height,
        templateDirectoryUri: siteData.templateDirectoryUri,
    }, [imageData.data.buffer]); // Transferable

    worker.onmessage = function (e) {
        if (e.data.success) {
            processBlobAsWebp(e.data.blob, alco, imageType, imageId);
        } else {
            console.error('Worker error:', e.data.error);
        }
        enableButtons(submitButtons);
        alco.showImageProcessingSpinner = false;
        alco.imageToProcess = false;
    };

}

function processBlobAsWebp(blob, alco, imageType, imageId) {
    if (blob) {
        var filename = `${alco._getImageData(imageType, imageId)['filename'].replace(/\.[^/.]+$/, '')}.webp`;
        var file = new File([blob], filename, { type: 'image/webp' });
        var croppedImageUrl = URL.createObjectURL(blob);
        updateImage(alco, imageType, imageId, croppedImageUrl, file);
    }
}
function browserSupportsWebPInCanvas() {
    var canvas = document.createElement('canvas');
    return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
}

function initCropper(alco, displayElement, imageType, imageId, submitButtons, displaySrc) {
    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;
    alco.showCropperDisplay = false;

    // Destroy existing cropper if it exists
    if (alco.cropper) { alco.cropper.destroy(); }

    // Only init cropper after the display loads
    displayElement.onload = function () {
        alco.currentImageId = imageId;
        alco.currentImageType = imageType;

        // Destroy existing cropper if it exists
        if (alco.cropper) { alco.cropper.destroy(); }

        alco.cropper = new Cropper(displayElement, {
            aspectRatio: 4 / 3,
            viewMode: 1,
            autoCropArea: 1,
            zoomable: 0,
            ready: function() { alco.showImageProcessingSpinner = false; },
            cropend: function() { alco.imageToProcess = true; },
        });
    };

    alco.showCropperDisplay = true;
    displayElement.src = displaySrc;

    // If it's already loaded (cached), fire manually
    if (displayElement.complete && displayElement.naturalWidth !== 0) {
        displayElement.onload();
    }

}
function initCropperFromFile(alco, event, displayElement, imageType, imageId, submitButtons) {

    // Disable submit button until image processing is complete
    disableButtons(submitButtons);
    alco.showImageProcessingSpinner = true;
    alco.showCropperDisplay = false;
    alco.imageToProcess = true;

    var files = event.target.files;

    if (files && files.length > 0) {
        if (!imageId) {
            imageId = generateRandomId();
        }
        var newImageData = {
            'image_id':  imageId,
            'file':      files[0],
            'filename':  files[0].name,
            'url':       '',
            'caption':   '',
            'mediatags': [],
        };
        addImage(alco, imageType, imageId, newImageData);

        var reader = new FileReader();
        reader.onload = function (evnt) {
            initCropper(alco, displayElement, imageType, imageId, submitButtons, evnt.target.result);
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
    var array = new Uint8Array(16);
    window.crypto.getRandomValues(array);
    return Array.from(array, b => b.toString(16).padStart(2, '0')).join('');
}
