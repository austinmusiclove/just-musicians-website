
import * as LibWebpWasm from 'libwebp-wasm';


let webpInstance = null;
async function init() {
    if (!webpInstance) {
        webpInstance = await LibWebpWasm;
    }
    return webpInstance;
}

export async function encode(rgba, width, height, quality = 75) {
  const webp = await init();
  return webp.encodeRGBA(rgba, width, height, quality);
}

