
function addYoutubeUrl(alco, input) {
    var value = input.value.trim();

    // Check for duplicate urls
    if (alco.youtubeVideoData.map(data => data.url).includes(value)) {
        alco.$dispatch('error-toast-youtube-link', {'message': 'This url has already been added'});
        input.value = '';
        return;
    }

    // Check for proper url format
    var validation = validateYoutubeUrl(value);
    if (validation !== true) {
        alco.$dispatch('error-toast-youtube-link', {'message': validation});
        input.value = '';
        return;
    }

    input.value = '';
    alco.youtubeVideoData.push({
        url:        value,
        mediatags:  [],
        start_time: 0,
        video_id:   getVideoIdFromUrl(value),
    });
    alco.currentYtIndex = alco.youtubeVideoData.length-1;
    alco.pVideoIds = getVideoIdsFromVideoData(alco.youtubeVideoData);
    alco.$dispatch('success-toast-youtube-link', {'message': 'Successfully added YouTube video'});
}


function removeYoutubeUrl(alco, index) {
    alco.youtubeVideoData.splice(index, 1);
    alco.pVideoIds = getVideoIdsFromVideoData(alco.youtubeVideoData);
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


function getVideoIdsFromVideoData(videoData) {
    var videoIds = [];

    videoData.forEach(data => {
        var videoId = getVideoIdFromUrl(data.url);
        if (videoId) { videoIds.push(videoId); }
    });

    return videoIds;
}
function getVideoIdFromUrl(url) {
    try {
        var parsedUrl = new URL(url);

        // Handle youtube.com/watch?v=...
        if (parsedUrl.hostname.includes('youtube.com')) {
            var videoId = parsedUrl.searchParams.get('v');
            if (videoId) { return videoId; }
        }

        // Handle youtu.be/VIDEO_ID
        else if (parsedUrl.hostname.includes('youtu.be')) {
            var id = parsedUrl.pathname.split('/')[1];
            if (id) { return id; }
        }
    } catch (e) {
        return null;
    }
}

function toggleYoutubeLinkTerm(alco, index, term) {
    var termIndex = alco.youtubeVideoData[index].mediatags.indexOf(term);
    if (termIndex == -1) {
        alco.youtubeVideoData[index].mediatags.push(term);
    } else {
        alco.youtubeVideoData[index].mediatags.splice(termIndex, 1);
    }
}
