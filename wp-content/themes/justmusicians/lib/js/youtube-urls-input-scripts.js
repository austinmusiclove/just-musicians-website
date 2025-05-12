
function addYoutubeUrl(alpineComponent, event) {
    var value = event.target.value.trim();

    // Check for duplicate urls
    if (alpineComponent.tags.includes(value)) {
        alpineComponent.$dispatch('youtube-url-error-toast', {'message': 'This url has already been added'});
        event.target.value = '';
        return;
    }

    // Check for proper url format
    var validation = validateYoutubeUrl(value);
    if (validation === true) {
        alpineComponent.tags.push(value);
        event.target.value = '';
        alpineComponent.pVideoIds = getVideoIdsFromUrls(alpineComponent.tags);
    } else {
        alpineComponent.$dispatch('youtube-url-error-toast', {'message': validation});
    }
}


function removeYoutubeUrl(alpineComponent, index) {
    alpineComponent.tags.splice(index, 1);
    alpineComponent.pVideoIds = getVideoIdsFromUrls(alpineComponent.tags);
}


function validateYoutubeUrl(url) {
    if (typeof url !== 'string' || url.trim() === '') {
        return 'URL must be a non-empty string';
    }

    let parsedUrl;
    try {
        parsedUrl = new URL(url);
    } catch (e) {
        return 'Invalid URL format';
    }

    // This will normalize the URL (e.g., remove trailing slashes, lowercase host, etc.)
    var normalizedUrl = parsedUrl.href;

    // Reject if input URL != normalized (handles concatenated/extra data)
    if (normalizedUrl !== url) {
        return 'URL contains unexpected or malformed content';
    }

    // Check correct host
    var validHosts = ['www.youtube.com', 'youtube.com', 'm.youtube.com', 'youtu.be'];
    if (!validHosts.includes(parsedUrl.hostname)) {
        return 'URL must be from youtube.com or youtu.be';
    }

    // Check Format
    var isWatchWithId = parsedUrl.pathname === '/watch' && parsedUrl.searchParams.has('v');
    var isShortLinkWithId = parsedUrl.hostname === 'youtu.be' && parsedUrl.pathname.length > 1;
    if (!isWatchWithId && !isShortLinkWithId) {
        return 'URL must contain a valid YouTube video ID';
    }

    // Strict match against exact formats we support
    var pattern = /^(https:\/\/)?(www\.|m\.)?(youtube\.com\/watch\?v=[\w-]{11}|youtu\.be\/[\w-]{11})([&?][^\s]*)?$/;
    if (!pattern.test(url.trim())) {
        return 'URL must be a valid YouTube video link';
    }

    return true;
}


function getVideoIdsFromUrls(urls) {
    var videoIds = [];

    urls.forEach(url => {
        try {
            var parsedUrl = new URL(url);

            // Handle youtube.com/watch?v=...
            if (parsedUrl.hostname.includes('youtube.com')) {
                var videoId = parsedUrl.searchParams.get('v');
                if (videoId) videoIds.push(videoId);
            }

            // Handle youtu.be/VIDEO_ID
            else if (parsedUrl.hostname.includes('youtu.be')) {
                var id = parsedUrl.pathname.split('/')[1];
                if (id) videoIds.push(id);
            }
        } catch (e) {
            // skip invalid URLs
            console.warn(`Invalid URL skipped: ${url}`);
        }
    });

    return videoIds;
}

