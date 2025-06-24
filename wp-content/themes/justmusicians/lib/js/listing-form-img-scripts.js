
function toggleImageTerm(alco, imageType, imageId, term) {
    var index = alco.imageData[imageType][imageId]['mediatags'].indexOf(term);
    if (index == -1) {
        alco.imageData[imageType][imageId]['mediatags'].push(term);
    } else {
        alco.imageData[imageType][imageId]['mediatags'].splice(index, 1);
    }
}

function addImage(alco, imageType, imageId, imageData) {
    alco.currentImageId = imageId;
    alco.imageData[imageType][imageId] = imageData;
    alco.orderedImages[imageType].push(imageId);
    updateTemplateInputs(alco);
}
function removeImage(alco, imageType, imageId) {
    if (imageType == 'cover_image') { alco.pThumbnailSrc = ''; }
    delete alco.imageData[imageType][imageId];

    var list = [...alco.orderedImages[imageType]]; // clone array to preserve reactivity
    var currentIndex = list.indexOf(imageId);
    if (currentIndex === -1) { return; }
    list.splice(currentIndex, 1);         // remove item
    alco.orderedImages[imageType] = list; // Replace with new array to trigger Alpine reactivity
    updateTemplateInputs(alco);
}
function reorderImage(alco, imageType, imageId, newPosition) {
    var list = [...alco.orderedImages[imageType]]; // clone array to preserve reactivity
    var currentIndex = list.indexOf(imageId);
    if (currentIndex === -1 || newPosition < 0 || newPosition >= list.length) { return; }
    list.splice(currentIndex, 1);         // remove item
    list.splice(newPosition, 0, imageId); // insert at new position
    alco.orderedImages[imageType] = list; // Replace with new array to trigger Alpine reactivity
}
function updateTemplateInputs(alco) {
    alco.listingImages = alco.orderedImages['listing_images'];
    alco.stagePlots = alco.orderedImages['stage_plots'];
}


function getAllMediatags(alco) {
    var tagsSet = new Set();
    for (var imageType in alco.imageData) {
        var images = alco.imageData[imageType];
        for (var imageId in images) {
            var image = images[imageId];
            if (Array.isArray(image.mediatags)) {
                image.mediatags.forEach(tag => tagsSet.add(tag));
            }
        }
    }
    return Array.from(tagsSet);
}

function updateImage(alco, imageType, imageId, url, file, wasCropped) {
    alco.imageData[imageType][imageId]['url'] = url;
    alco.imageData[imageType][imageId]['file'] = file;
    if (wasCropped) { alco.imageData[imageType][imageId]['attachment_id'] = ''; }
    if (imageId == 'cover_image') { alco.pThumbnailSrc = url; }
    updateFileInputs(alco);
}

function updateFileInputs(alco) {
    var imageTypes = Object.keys(alco.imageData);
    imageTypes.forEach((imageType) => {
        var dataTransfer = new DataTransfer();

        var uploadIndex = 0;
        Object.entries(alco.imageData[imageType]).forEach(([imageId, imageData]) => {
            if (imageData.file) {
                dataTransfer.items.add(imageData.file);
                alco.imageData[imageType][imageId].upload_index = uploadIndex;
                uploadIndex++;
            }
        });

        // Assign files to the file input
        alco.$refs[`${imageType}_file`].files = dataTransfer.files;
    });
}

// TODO generalize function by adding image id for cover in the payload
// Triggered by html api response; updates attachmentIds for newly uploaded images
function updateAttachmentIds(alco, attachmentIds) {
    if (attachmentIds['cover_image']) { alco.imageData['cover_image']['cover_image']['attachment_id'] = attachmentIds['cover_image']; }
    for (var imageId in attachmentIds['listing_images']) {
        if (alco.imageData['listing_images'][imageId]) {
            alco.imageData['listing_images'][imageId]['attachment_id'] = attachmentIds['listing_images'][imageId];
        }
    }
    for (var imageId in attachmentIds['stage_plots']) {
        if (alco.imageData['stage_plots'][imageId]) {
            alco.imageData['stage_plots'][imageId]['attachment_id'] = attachmentIds['stage_plots'][imageId];
        }
    }
}

