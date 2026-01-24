function emphasizeElm(alco, elm, elmId) {
    if (!elm && elmId) {
        elm = document.getElementById(elmId);
    }

    if (!elm) return; // still not found, bail out safely

    elm.focus();
    alco.shakeElements.add(elmId);
    setTimeout(() => alco.shakeElements.delete(elmId), 400);
}
