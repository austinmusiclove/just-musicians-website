
function addYoutubeUrl(alco, input) {
    var value = input.value.trim();

    // Check for duplicate urls
    if (alco.youtubeVideoData.map(data => data.url).includes(value)) {
        alco.$dispatch('error-toast-youtube-link', {'message': 'This url has already been added'});
        input.value = '';
        return;
    }

    // Check for proper url format
    var isValid = isValidYoutubeUrl(value);
    if (isValid !== true) {
        alco.$dispatch('error-toast-youtube-link', {'message': isValid});
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
    alco.$dispatch('success-toast-youtube-link', {'message': 'Successfully added YouTube video'});
}


function removeYoutubeUrl(alco, index) {
    alco.youtubeVideoData.splice(index, 1);
}


/*** Valid Youtube URL examples
https://www.youtube.com/watch?time_continue=1&v=PsTm7r4Ijgc&embeds_referring_euri=https%3A%2F%2Fjimmyeatbrisket.com%2F&source_ve_path=Mjg2NjY
https://www.youtube.com/watch?v=keKIhiCGl48&list=PLBJm7uMkVCGi7nXHCLr0ke4_bfD5Qf5Iv&index=8
https://www.youtube.com/watch?list=PLBJm7uMkVCGi7nXHCLr0ke4_bfD5Qf5Iv&v=SnLHff64gNk
https://www.youtube.com/watch?v=ivt9ypn_4kQ&ab_channel=AdamDodson
https://www.youtube.com/watch?v=Onrg9ndx2Vs&feature=youtu.be
https://www.youtube.com/watch?app=desktop&v=4GBwBY272nc
https://www.youtube.com/watch?v=3TBAgTSqQuw&t=16s
https://www.youtube.com/watch?v=v9C3UHya0EY
https://youtube.com/watch?v=QhEOTjfa-wA&si=ep4Sz0B6tudIKRk5
https://youtu.be/qzAZgdrNttY
https://youtu.be/x-8zu6obNNA
https://youtu.be/kNtVnhFNA-M?feature=shared
https://youtu.be/zMgyVfpxJpE?si=j2KgWyFT4gc5XU0X
https://m.youtube.com/watch?v=w7lX4VUOecw
https://m.youtube.com/watch?v=jAWWcnpc_RY&pp=ygUQRGlldHJpY2ggY2FsaG91bg%3D%3D
*/
function isValidYoutubeUrl(url) {
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

    // Check video id
    var videoId = '';
    if (isWatchWithId)     { videoId = parsedUrl.searchParams.get('v'); }
    if (isShortLinkWithId) { videoId = parsedUrl.pathname.slice(1); }
    if (!/^[a-zA-Z0-9_-]{11}$/.test(videoId)) {
        return 'Youtu.be URL is missing valid video Id';
    }

    return true;
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
