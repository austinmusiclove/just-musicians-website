// imageProcessor.worker.js

/**
 * Helper function to scale down an image buffer to a maximum width while preserving aspect ratio.
 */
function getResizedCanvas(imageData, width, height, maxWidth = 1024) {
    let targetWidth = width;
    let targetHeight = height;

    if (width > maxWidth) {
        targetWidth = maxWidth;
        targetHeight = Math.round(height * (maxWidth / width));
    }

    // 1. Put original raw pixel data onto a temporary source canvas
    const tempCanvas = new OffscreenCanvas(width, height);
    const tempCtx = tempCanvas.getContext('2d');
    tempCtx.putImageData(new ImageData(new Uint8ClampedArray(imageData), width, height), 0, 0);

    // 2. Create the destination canvas with target dimensions and draw downscaled image
    const targetCanvas = new OffscreenCanvas(targetWidth, targetHeight);
    const targetCtx = targetCanvas.getContext('2d');
    targetCtx.drawImage(tempCanvas, 0, 0, width, height, 0, 0, targetWidth, targetHeight);

    return targetCanvas;
}

self.onmessage = async function (e) {
    const { type, imageData, width, height, templateDirectoryUri } = e.data;

    try {
        if (type === 'libwebp') {
            // Resize using shared helper
            const resizedCanvas = getResizedCanvas(imageData, width, height, 1024);
            const ctx = resizedCanvas.getContext('2d');
            const resizedImgData = ctx.getImageData(0, 0, resizedCanvas.width, resizedCanvas.height);

            // Load WASM module and encode
            await importScripts(`${templateDirectoryUri}/dist/webp.js`);
            const webpBuffer = await WebPModule.encode(
                resizedImgData.data,
                resizedCanvas.width,
                resizedCanvas.height,
                75
            );

            const blob = new Blob([webpBuffer], { type: 'image/webp' });
            self.postMessage({ success: true, blob });
        }

        if (type === 'canvas') {
            // Resize using shared helper, then export with quality 0.8
            const resizedCanvas = getResizedCanvas(imageData, width, height, 1024);
            const blob = await resizedCanvas.convertToBlob({
                type: 'image/webp',
                quality: 0.8
            });

            self.postMessage({ success: true, blob });
        }

    } catch (err) {
        self.postMessage({ success: false, error: err.message });
    }
};
