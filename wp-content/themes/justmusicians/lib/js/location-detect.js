(function () {
    var STORAGE_KEY = 'hm_detected_location';
    var TTL = 24 * 60 * 60 * 1000;
    var ENDPOINT = window.hmLocationDetect && window.hmLocationDetect.endpoint;

    function readCached() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function writeCache(detail) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                lat: detail.lat,
                lng: detail.lng,
                label: detail.label,
                ts: Date.now()
            }));
        } catch (e) {}
    }

    function emit(detail) {
        window.dispatchEvent(new CustomEvent('location-detected', { detail: detail }));
    }

    function detect() {
        var cached = readCached();
        if (cached && cached.ts && (Date.now() - cached.ts) < TTL && cached.label) {
            emit({ lat: cached.lat, lng: cached.lng, label: cached.label });
            return;
        }

        if (!ENDPOINT) { return; }

        fetch(ENDPOINT, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                var detail = { lat: data.lat, lng: data.lng, label: data.label };
                writeCache(detail);
                emit(detail);
            })
            .catch(function () {
                if (cached && cached.label) {
                    emit({ lat: cached.lat, lng: cached.lng, label: cached.label });
                }
            });
    }

    var started = false;
    var start = function () {
        if (started) { return; }
        started = true;
        detect();
    };

    document.addEventListener('alpine:initialized', start);
    if (document.readyState === 'complete') { start(); }
    window.addEventListener('load', start);
})();