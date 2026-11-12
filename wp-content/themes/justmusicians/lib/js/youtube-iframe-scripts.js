// Configuration
var DELAY_MS = 10000;

// State flags
var youtubeIframeApiReady = false;
var delayElapsed = false;
var initQueue = [];
var clearingQueue = false;
var hasTriggered = false;

// Interaction events to watch for
var interactionEvents = ['pointerdown', 'keydown', 'touchstart', 'scroll', 'click'];

// Unified trigger function for interaction or timer
function handleDelayOrInteraction() {
    if (hasTriggered) { return; }
    hasTriggered = true;
    delayElapsed = true;

    // Clean up event listeners once triggered
    interactionEvents.forEach(function (eventName) {
        window.removeEventListener(eventName, handleDelayOrInteraction, { passive: true });
    });

    tryProcessQueue();
}

// Fallback static delay timer
setTimeout(function () {
    handleDelayOrInteraction();
}, DELAY_MS);

// Watch for first user interaction
interactionEvents.forEach(function (eventName) {
    window.addEventListener(eventName, handleDelayOrInteraction, { passive: true });
});

// Watch for YouTube API ready
window.onYouTubeIframeAPIReady = function () {
    youtubeIframeApiReady = true;
    tryProcessQueue();
};

// Helper to check if both conditions are met before clearing queue
function tryProcessQueue() {
    if (youtubeIframeApiReady && delayElapsed) {
        clearInitQueue();
    }
}

// Clear the queue
function clearInitQueue() {
    if (clearingQueue) { return; }
    clearingQueue = true;

    while (initQueue.length > 0) {
        var queueItem = initQueue.shift();
        initPlayer(queueItem.alpineComponent, queueItem.playerId, queueItem.videoData);
    }
    clearingQueue = false;
}

function initPlayer(alpineComponent, playerId, videoData) {
    if (!youtubeIframeApiReady || !delayElapsed) {
        initQueue.push({ alpineComponent, playerId, videoData });
        return;
    }

    if (playerId) {
        var player = new YT.Player(playerId, {
            host: 'https://www.youtube-nocookie.com',
            videoId: videoData.video_id, // remove this to init from iframe
            width: '100%',
            height: '100%',
            playerVars: {
                controls: 1, // 0 for legacy media slider
                origin: siteData.siteUrl,
                enablejsapi: 1,
                mute: 0,
                autoplay: 0,
                rel: 0,
                start: videoData?.start_time || 0,
            },
            events: {
                "onReady": () => {
                    alpineComponent.players[playerId].isReady = true;
                    //if (!alpineComponent.playersMuted) { alpineComponent.players[playerId].unMute(); }
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

                    alpineComponent.players[playerId].playerState = event.data; // Not setting until state change to distinguish between pre-play (undefined) and UNSTARTED (-1)
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

function setupVisibilityListener(alpineComponent) {
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pauseAllPlayers(alpineComponent);
        }
    });
}
function clearAllPlayers(alpineComponent) {
    alpineComponent.players = {};
}
function pauseAllPlayers(alpineComponent) {
    Object.values(alpineComponent.players).forEach((player) => {
        pausePlayer(player);
    });
}
function pausePlayerById(alpineComponent, playerId) {
    if (playerId && alpineComponent.players[playerId]) {
        pausePlayer(alpineComponent.players[playerId]);
    }
}
function pausePlayer(player) {
    if (player && player.isReady) {
        if (player.playerState == YT.PlayerState.UNSTARTED ||
            player.playerState == YT.PlayerState.CUED      ||
            player.playerState == YT.PlayerState.BUFFERING
        ) {
            player.mute();
            player.pauseNextPlay = true;
        } else {
            player.pauseVideo();
            player.isPaused = true; // redundant but this usually fires before onStateChange creating a snappier UX
            player.playerState = YT.PlayerState.PAUSED;
        }
    }
}
function playPlayer(alpineComponent, playerId) {
    if (playerId && alpineComponent.players[playerId] && alpineComponent.players[playerId].isReady) {
        alpineComponent.players[playerId].pauseNextPlay = false;
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

function youTubeIframeContainerData() {
    return {
        players: {},
        playersMuted: false,
        playersPaused: false,
        _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
        _clearAllPlayers()             { clearAllPlayers(this); },
        _pauseAllPlayers()             { pauseAllPlayers(this); },
        _pausePlayerById(playerId)     { pausePlayerById(this, playerId); },
        _playPlayer(playerId)          { playPlayer(this, playerId); },
        _toggleMute()                  { toggleMute(this); },
        _setupVisibilityListener()     { setupVisibilityListener(this); },
    }
}

