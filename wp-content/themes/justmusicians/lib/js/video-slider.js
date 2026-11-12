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
function isPaused(alco) {
    var currentPlayerId = getCurrentPlayerId(alco);
    if (currentPlayerId && currentPlayerId in alco.players) {
        return alco.currentIndex > 0 && alco.players[getCurrentPlayerId(alco)].isPaused;
    } else {
        return false;
    }
}
function enterSlider(alco) {
    if (!isTouchDevice) {
        alco.showArrows = true;
    }
}
function leaveSlider(alco) {
    if (!isTouchDevice) {
        alco.showArrows = false;
    }
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

function videoSliderData(config = {}) {
    return {
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: config.totalSlides ?? 0,
        videoData:   config.videoData ?? [],
        playerIds: {},
        _updateIndex(newIndex)  { updateIndex(this, newIndex); },
        _pausePreviousSlide()   { pausePreviousSlide(this); },
        _pauseCurrentSlide()    { pauseCurrentSlide(this); },
        _playCurrentSlide()     { playCurrentSlide(this); },
        _isPaused()             { return isPaused(this); },
        _enterSlider()          { enterSlider(this); },
        _leaveSlider()          { leaveSlider(this); },
        _updateVideoSliderData(videoData) {
            this._updateIndex(0);
            this.videoData = videoData;
            this.totalSlides = videoData.length;
        },
    };
}
