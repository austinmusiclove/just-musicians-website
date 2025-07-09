function getImageData(alco, imageType, imageId) {
    var group = alco.orderedImageData[imageType];
    if (!Array.isArray(group)) return null;
    return group.find(item => item.image_id === imageId) || null;
}

function getIndexOfId(arr, id, idName) {
    return arr.findIndex(item => item[idName] === id);
}

function toggleImageTerm(alco, imageType, imageId, term) {
    var tagIndex = getImageData(alco, imageType, imageId)['mediatags'].indexOf(term);
    var imageIndex = getIndexOfId(alco.orderedImageData[imageType], imageId, 'image_id');

    if (tagIndex == -1) {
        alco.orderedImageData[imageType][imageIndex]['mediatags'].push(term);
    } else {
        alco.orderedImageData[imageType][imageIndex]['mediatags'].splice(tagIndex, 1);
    }
}

function addImage(alco, imageType, imageId, imageData) {
    alco.currentImageId = imageId;
    if (imageType == 'cover_image') {
        alco.orderedImageData['cover_image'] = [imageData];
    } else {
        alco.orderedImageData[imageType].push(imageData);
    }
}

function removeImage(alco, imageType, imageId) {
    if (imageType == 'cover_image') { alco.pThumbnailSrc = ''; }

    var imageIndex = getIndexOfId(alco.orderedImageData[imageType], imageId, 'image_id');
    if (imageIndex === -1) { return; }
    var list = [...alco.orderedImageData[imageType]];  // copy list
    list.splice(imageIndex, 1);                        // remove item
    alco.orderedImageData[imageType] = list            // update data;
}

function getAllMediatags(alco) {
    var tagsSet = new Set();
    for (var imageType of Object.keys(alco.orderedImageData)) {
        for (var image of alco.orderedImageData[imageType]) {
            if (Array.isArray(image.mediatags)) {
                image.mediatags.forEach(tag => tagsSet.add(tag));
            }
        }
    }
    for (var videoData of alco.youtubeVideoData) {
        if (Array.isArray(videoData.mediatags)) {
            videoData.mediatags.forEach(tag => tagsSet.add(tag));
        }
    }
    return Array.from(tagsSet);
}

function updateImage(alco, imageType, imageId, url, file, wasCropped) {
    var imageIndex = getIndexOfId(alco.orderedImageData[imageType], imageId, 'image_id');
    alco.orderedImageData[imageType][imageIndex]['url'] = url;
    alco.orderedImageData[imageType][imageIndex]['file'] = file;
    if (wasCropped) { alco.orderedImageData[imageType][imageIndex]['attachment_id'] = ''; }
    if (imageId == 'cover_image') { alco.pThumbnailSrc = url; }
    updateFileInputs(alco);
}

function updateFileInputs(alco) {
    var imageTypes = Object.keys(alco.orderedImageData);
    imageTypes.forEach((imageType) => {
        var dataTransfer = new DataTransfer();

        var uploadIndex = 0;
        alco.orderedImageData[imageType].forEach((data) => {
            if (data.file) {
                var imageIndex = getIndexOfId(alco.orderedImageData[imageType], data.image_id, 'image_id');
                dataTransfer.items.add(data.file);
                alco.orderedImageData[imageType][imageIndex].upload_index = uploadIndex;
                uploadIndex++;
            }
        });

        // Assign files to the file input
        alco.$refs[`${imageType}_file`].files = dataTransfer.files;
    });
}

// Triggered by html api response; updates attachmentIds for newly uploaded images
function updateAttachmentIds(alco, attachmentIds) {
    if (attachmentIds['cover_image']) {
        alco.orderedImageData['cover_image'][0]['attachment_id'] = attachmentIds['cover_image'];
    }
    for (var imageId in attachmentIds['listing_images']) {
        var imageIndex = getIndexOfId(alco.orderedImageData['listing_images'], imageId, 'image_id');
        if (alco.orderedImageData['listing_images'][imageIndex]) {
            alco.orderedImageData['listing_images'][imageIndex]['attachment_id'] = attachmentIds['listing_images'][imageId];
        }
    }
    for (var imageId in attachmentIds['stage_plots']) {
        var imageIndex = getIndexOfId(alco.orderedImageData['stage_plots'], imageId, 'image_id');
        if (alco.orderedImageData['stage_plots'][imageIndex]) {
            alco.orderedImageData['stage_plots'][imageIndex]['attachment_id'] = attachmentIds['stage_plots'][imageId];
        }
    }
    for (var videoId in attachmentIds['youtube_videos']) {
        var videoIndex = getIndexOfId(alco.youtubeVideoData, videoId, 'video_id');
        if (alco.youtubeVideoData[videoIndex]) {
            alco.youtubeVideoData[videoIndex]['post_id'] = attachmentIds['youtube_videos'][videoId];
        }
    }
}
