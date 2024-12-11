class YouTubeHelper {
    constructor (helper) {
        this.helper = helper;
    }
    isYoutubeUrl(url) {
        if (!this.helper.isValidUrl(url)) { return false; }
        let urlObject = new URL(url);
        return urlObject.hostname.includes('youtube.com') || urlObject.hostname.includes('youtu.be');
    }
    getVideoIdFromYoutubeUrl(url) {
        if (!this.isYoutubeUrl(url)) { return false; }
        let youtubeUrl = new URL(url);
        return youtubeUrl.searchParams.get('v');
    }
    addYoutubeIframe(iframeId, videoUrl, controls=1, autoplay=0) {
        let videoId = this.getVideoIdFromYoutubeUrl(videoUrl);
        let player = new YT.Player(iframeId, {
            width: '384',
            height: '216',
            videoId: videoId,
            playerVars: {
                'playsinline': 1,
                'autoplay': autoplay,
                'controls': controls,
                'disablekb': 1,
                'rel': 0,
                'iv_load_policy': 3,
                'mute': 1,
            },
            events: {
                'onReady': function () {},
            }
        });
    }
}

export default YouTubeHelper;

