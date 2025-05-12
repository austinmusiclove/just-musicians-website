function listenForYoutubeIframeApiReady(alpineComponent) {
    document.addEventListener('youtube-api-ready', () => {
        alpineComponent.playerIds.forEach((playerId) => {
            if (playerId) { alpineComponent.$dispatch('init-youtube-player', {'playerId': playerId}); }
        });
    }, {once: true});
    if (typeof YT != 'undefined') {
        alpineComponent.$dispatch('youtube-api-ready');
    }
}

function initPlayerFromIframe(alpineComponent, playerId) {
    if (playerId) {
        var player = new YT.Player(playerId, {
            playerVars: {
                controls: 0,
                origin: "<?php echo site_url(); ?>",
                enablejsapi: 1,
            },
            events: {
                "onReady": () => {
                    alpineComponent.players[playerId].isReady = true;
                    if (!alpineComponent.playersMuted) { alpineComponent.players[playerId].unMute(); }
                },
                "onStateChange": (event) => {
                    alpineComponent.players[playerId].state = event.data;
                    if (event.data == 1) { alpineComponent.players[playerId].isPaused = false; } // 1 is playing
                    if (event.data == 2) { alpineComponent.players[playerId].isPaused = true; } // 2 is paused
                }
            }
        });
        alpineComponent.players[playerId] = player;
    }
}


function pausePlayer(alpineComponent, playerId) {
    if (playerId && alpineComponent.players[playerId] && alpineComponent.players[playerId].isReady) {
        var playerState = alpineComponent.players[playerId].state;
        if (playerState == -1 || playerState == 3) { // -1 is unstarted and 3 is buffering
            alpineComponent.players[playerId].stopVideo();
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
