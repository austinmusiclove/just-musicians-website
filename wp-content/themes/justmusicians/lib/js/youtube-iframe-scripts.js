// Watch for api ready
var youtubeIframeApiReady = false;
var initQueue = [];
window.onYouTubeIframeAPIReady = function () {
    youtubeIframeApiReady = true;
    clearInitQueue();
    setTimeout(clearInitQueue(), 1000); // empty queue again to avoid race condition of something being added after the queue is cleared the first time
};


// Init players that were ready for init before the api was ready
var clearingQueue = false;
function clearInitQueue() {
    if (clearingQueue) { return; } else { clearingQueue = true; } // don't allow this function to run twice at the same time

    // clear the queue
    while (initQueue.length > 0) {
        var queueItem = initQueue.shift();
        initPlayer(queueItem.alpineComponent, queueItem.playerId, queueItem.videoId);
    }
    clearingQueue = false;
}


function initPlayer(alpineComponent, playerId, videoId) {
    if (!youtubeIframeApiReady) {
        // If the api is not ready, add this init call to a queue
        initQueue.push({ alpineComponent, playerId, videoId });
        return;
    }
    if (playerId) {
        var player = new YT.Player(playerId, {
            videoId: videoId, // remove this to init from iframe
            playerVars: {
                controls: 0,
                origin: siteData.siteUrl,
                enablejsapi: 1,
                mute: 1,
                autoplay: 0,
                rel: 0,
            },
            events: {
                "onReady": () => {
                    alpineComponent.players[playerId].isReady = true;
                    if (!alpineComponent.playersMuted) { alpineComponent.players[playerId].unMute(); }
                },
                "onStateChange": (event) => {
                    /*
                    if (event.data == YT.PlayerState.UNSTARTED) { console.log('unstarted'); }
                    if (event.data == YT.PlayerState.ENDED)     { console.log('ended'); }
                    if (event.data == YT.PlayerState.PLAYING)   { console.log('playing'); }
                    if (event.data == YT.PlayerState.PAUSED)    { console.log('paused'); }
                    if (event.data == YT.PlayerState.BUFFERING) { console.log('buffering'); }
                    if (event.data == YT.PlayerState.CUED)      { console.log('cued'); }
                    */

                    if (event.data == YT.PlayerState.PAUSED)  { alpineComponent.players[playerId].isPaused = true; }
                    if (event.data == YT.PlayerState.PLAYING) { alpineComponent.players[playerId].isPaused = false; }
                    if (event.data == YT.PlayerState.PLAYING) {
                        if ( alpineComponent.players[playerId].pauseNextPlay ) {
                            alpineComponent.players[playerId].pauseVideo();
                            alpineComponent.players[playerId].pauseNextPlay = false;
                        }
                    }
                },
                "onError": (event) => {
                    //console.error(event.data);
                }
            }
        });
        alpineComponent.players[playerId] = player;
    }

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


function setupVisibilityListener(alpineComponent) {
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            alpineComponent.$dispatch('pause-all-youtube-players');
        }
    });
}


function pauseAllPlayers(alpineComponent) {
    Object.values(alpineComponent.players).forEach((player) => {
        if (player.isReady) { player.pauseVideo(); }
    });
}


function pausePlayer(alpineComponent, playerId) {
    if (playerId && alpineComponent.players[playerId] && alpineComponent.players[playerId].isReady) {
        var playerState = alpineComponent.players[playerId].getPlayerState();
        if (playerState == YT.PlayerState.UNSTARTED ||
            playerState == YT.PlayerState.CUED      ||
            playerState == YT.PlayerState.BUFFERING
        ) {
            alpineComponent.players[playerId].pauseNextPlay = true;
        } else {
            alpineComponent.players[playerId].pauseVideo();
        }
        alpineComponent.players[playerId].isPaused = true; // redundant but this usually fires before onStateChange creating a snappier UX
    }
}


function playPlayer(alpineComponent, playerId) {
    if (playerId && alpineComponent.players[playerId] && alpineComponent.players[playerId].isReady) {
        alpineComponent.players[playerId].playVideo();
        alpineComponent.players[playerId].isPaused = false; // redundant but this usually fires before onStateChange creating a snappier UX
    }
}


function toggleMute(alpineComponent) {
    if (alpineComponent.playersMuted) {
        Object.values(alpineComponent.players).forEach((player) => {if (player.isReady) { player.unMute(); }});
    } else {
        Object.values(alpineComponent.players).forEach((player) => {if (player.isReady) { player.mute(); }});
    }
    alpineComponent.playersMuted = !alpineComponent.playersMuted;
}
