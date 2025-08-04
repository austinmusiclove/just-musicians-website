// imageProcessor.worker.js

self.onmessage = async function (e) {
    const { type, imageData, width, height, templateDirectoryUri } = e.data;

    try {
        if (type === 'libwebp') {
            // Use wasm
            await importScripts(`${templateDirectoryUri}/dist/webp.js`);
            const webpBuffer = await WebPModule.encode(new Uint8ClampedArray(imageData), width, height, 75);
            const blob = new Blob([webpBuffer], { type: 'image/webp' });
            self.postMessage({ success: true, blob });
        }

        if (type === 'canvas') {
            // Recreate canvas and draw image from ImageData
            const offscreen = new OffscreenCanvas(width, height);
            const ctx = offscreen.getContext('2d');
            const imgData = new ImageData(new Uint8ClampedArray(imageData), width, height);
            ctx.putImageData(imgData, 0, 0);

            const blob = await new Promise(resolve => offscreen.convertToBlob({ type: 'image/webp' }).then(resolve));
            self.postMessage({ success: true, blob });
        }

    } catch (err) {
        self.postMessage({ success: false, error: err.message });
    }
};
