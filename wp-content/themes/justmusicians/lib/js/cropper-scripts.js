
function processNewImage(alco, imageType, imageId) {
    setImageData(alco, imageType, imageId, 'loading', true);

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
    setImageData(alco, imageType, imageId, 'worker', worker);
    worker.postMessage({
        type:                 processingType,
        imageData:            imageData.data.buffer,
        width:                croppedCanvas.width,
        height:               croppedCanvas.height,
        templateDirectoryUri: siteData.templateDirectoryUri,
    }, [imageData.data.buffer]);

    worker.onmessage = async function (e) {
        if (e.data.success) {
            await processBlobAsWebp(e.data.blob, alco, imageType, imageId);
            // Submit form if user is not actively cropping
            if (alco.showImageEditPopup == false && alco.showStagePlotPopup == false) {
                alco.$refs.listingForm.dispatchEvent( new Event('submit', { bubbles: true, cancelable: true }) ); // Submit form
            }
        } else {
            alco.$dispatch('error-toast', { 'message': 'Failed to process image'});
            // Remove image if it is newly uploaded image
            if (alco._getImageData(imageType, imageId).url == '') {
                removeImage(alco, imageType, imageId);
            }
            if (alco.showImageEditPopup) { alco.showImageEditPopup = false; }
            if (alco.showStagePlotPopup) { alco.showStagePlotPopup = false; }
        }
        setImageData(alco, imageType, imageId, 'loading', false);
        setImageData(alco, imageType, imageId, 'worker', null);
    };

}

async function processBlobAsWebp(blob, alco, imageType, imageId) {
    if (blob) {
        var filename = `${alco._getImageData(imageType, imageId)['filename'].replace(/\.[^/.]+$/, '')}.webp`;
        var file = new File([blob], filename, { type: 'image/webp' });
        var croppedImageUrl = URL.createObjectURL(blob);
        await updateImage(alco, imageType, imageId, croppedImageUrl, file);
    }
}
function browserSupportsWebPInCanvas() {
    var canvas = document.createElement('canvas');
    return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
}

function handleCropEnd(alco, imageType, imageId) {
    var imageData = getImageData(alco, imageType, imageId);
    if (imageData.worker) {
        imageData.worker.terminate();
    }
    processNewImage(alco, imageType, imageId);
}
function handleCropperReady(alco, imageType, imageId, newFile) {
    alco.popupImageSpinner = false;
    if (newFile) {
        processNewImage(alco, imageType, imageId);
    }
}

function initCropper(alco, displayElement, imageType, imageId, displaySrc, newFile) {
    // Disable submit button until image processing is complete
    alco.popupImageSpinner = true;
    alco.showCropperDisplay = false;

    // Destroy existing cropper if it exists
    if (alco.cropper) { alco.cropper.destroy(); }

    // Only init cropper after the display loads
    displayElement.onload = function () {
        alco.currentImageId = imageId;

        // Destroy existing cropper if it exists
        if (alco.cropper) { alco.cropper.destroy(); }

        alco.cropper = new Cropper(displayElement, {
            aspectRatio: 4 / 3,
            viewMode: 1,
            autoCropArea: 1,
            zoomable: 0,
            ready: function() { handleCropperReady(alco, imageType, imageId, newFile); },
            cropend: function() { handleCropEnd(alco, imageType, imageId); },
        });
    };

    alco.showCropperDisplay = true;
    displayElement.src = displaySrc;

    // If it's already loaded (cached), fire manually
    if (displayElement.complete && displayElement.naturalWidth !== 0) {
        displayElement.onload();
    }

}
function initCropperFromFile(alco, event, displayElement, imageType, imageId) {

    var files = event.target.files;

    if (files && files.length > 0) {

        alco.popupImageSpinner = true;
        alco.showCropperDisplay = false;

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
            'loading':   true,
            'worker':    null,
        };
        addImage(alco, imageType, imageId, newImageData);

        var reader = new FileReader();
        reader.onload = function (evnt) {
            initCropper(alco, displayElement, imageType, imageId, evnt.target.result, true);
        };

        reader.readAsDataURL(files[0]);
    }
}
