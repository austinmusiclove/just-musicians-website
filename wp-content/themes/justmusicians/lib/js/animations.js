function emphasizeElm(alco, elm, elmId) {
    elm.focus();
    alco.shakeElements.add(elmId);
    setTimeout(() => alco.shakeElements.delete(elmId), 400);
}
