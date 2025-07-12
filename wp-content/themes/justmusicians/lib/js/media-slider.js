function pausePreviousSlide(alco) {
    if (getPreviousPlayerId(alco)) {
        alco.$dispatch('pause-youtube-player', {'playerId': getPreviousPlayerId(alco)});
    }
}

function pauseCurrentSlide(alco) {
    if (getCurrentPlayerId(alco)) {
        alco.$dispatch('pause-youtube-player', {'playerId': getCurrentPlayerId(alco)});
    }
}

function playCurrentSlide(alco) {
    if (getCurrentPlayerId(alco)) {
        alco.$dispatch('play-youtube-player', {'playerId': getCurrentPlayerId(alco)});
    }
}

function toggleMuteAllVideos(alco) {
    alco.$dispatch('mute-youtube-players');
}

function isPaused(alco) {
    var currentPlayerId = getCurrentPlayerId(alco);
    if (currentPlayerId && currentPlayerId in alco.players) {
        return alco.currentIndex > 0 && alco.players[getCurrentPlayerId(alco)].isPaused;
    } else {
        return false;
    }
}

function enterSlider(alco) {
    playCurrentSlide(alco);
    alco.showArrows = true;
}

function leaveSlider(alco) {
    pauseCurrentSlide(alco);
    alco.showArrows = false;
}

function updateIndex(alco, newIndex) {
    alco.previousIndex = alco.currentIndex;
    alco.currentIndex = newIndex;
}

function getCurrentPlayerId(alco) {
    if (alco.currentIndex in alco.playerIds) {
        return alco.playerIds[alco.currentIndex];
    } else {
        return false;
    }
}

function getPreviousPlayerId(alco) {
    if (alco.previousIndex in alco.playerIds) {
        return alco.playerIds[alco.previousIndex];
    } else {
        return false;
    }
}
