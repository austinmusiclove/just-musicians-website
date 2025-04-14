function initPlayer(alpineComponent, playerId, videoId) {
    if (playerId) {
        var player = new YT.Player(playerId, {
            videoId: videoId, // remove this to init from iframe
            playerVars: {
                controls: 0,
                origin: siteData.siteUrl,
                enablejsapi: 1,
                mute: 1,
            },
            events: {
                "onReady": () => {
                    alpineComponent.players[playerId].isReady = true;
                    if (!alpineComponent.playersMuted) { alpineComponent.players[playerId].unMute(); }
                },
                "onStateChange": (event) => {
                    /*
                    if (event.data == YT.PlayerState.UNSTARTED) { console.log('new state unstarted');}
                    if (event.data == YT.PlayerState.ENDED)     { console.log('new state ended');}
                    if (event.data == YT.PlayerState.PLAYING)   { console.log('new state playing');}
                    if (event.data == YT.PlayerState.PAUSED)    { console.log('new state paused');}
                    if (event.data == YT.PlayerState.BUFFERING) { console.log('new state buffering');}
                    if (event.data == YT.PlayerState.CUED)      { console.log('new state cued');}
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
