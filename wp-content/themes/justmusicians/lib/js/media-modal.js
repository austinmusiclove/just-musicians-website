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
function openMediaModal(alco) {
    alco.showMediaModal = true;
}
function closeMediaModal(alco) {
    alco.showMediaModal = false;
    alco.$dispatch('pause-all-youtube-players');
}

function mediaModalData(config = {}) {
    return {
        showMediaModal: false,
        previousIndex: 0,
        currentIndex: 0,
        showArrows: isTouchDevice,
        totalSlides: 0,
        videoData: [],
        playerIds: {},
        currentPostId: 0,
        _updateIndex(newIndex)  { updateIndex(this, newIndex); },
        _pausePreviousSlide()   { pausePreviousSlide(this); },
        _pauseCurrentSlide()    { pauseCurrentSlide(this); },
        _playCurrentSlide()     { playCurrentSlide(this); },
        _isPaused()             { return isPaused(this); },
        _enterSlider()          { enterSlider(this); },
        _leaveSlider()          { leaveSlider(this); },
        _closeMediaModal()      { closeMediaModal(this); },
        _openMediaModal()       { openMediaModal(this); },
        _updateVideoData(videoData, postId) {
            this.previousIndex = 0;
            this.currentIndex = 0;
            if (postId != this.currentPostId) {
                this.$dispatch('clear-all-youtube-players');
                this.currentPostId = postId;
                this.playerIds = {};
                this.totalSlides = videoData.length;
                this.videoData = videoData;
            }
        },
    };
}
