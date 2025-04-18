
function pausePreviousSlide(alpineComponent) {
    if (alpineComponent.previousIndex > 0) {
        alpineComponent.$dispatch('pause-youtube-player', {'playerId': getPreviousPlayerId(alpineComponent)});
    }
}

function pauseCurrentSlide(alpineComponent) {
    if (alpineComponent.currentIndex > 0) {
        alpineComponent.$dispatch('pause-youtube-player', {'playerId': getCurrentPlayerId(alpineComponent)});
    }
}

function playCurrentSlide(alpineComponent) {
    if (alpineComponent.currentIndex > 0) {
        alpineComponent.$dispatch('play-youtube-player', {'playerId': getCurrentPlayerId(alpineComponent)});
    }
}

function toggleMuteAllVideos(alpineComponent) {
    alpineComponent.$dispatch('mute-youtube-players');
}

function isPaused(alpineComponent) {
    return alpineComponent.currentIndex > 0 && alpineComponent.players[getCurrentPlayerId(alpineComponent)].isPaused;
}

function enterSlider(alpineComponent) {
    playCurrentSlide(alpineComponent);
    alpineComponent.showArrows = true;
}

function leaveSlider(alpineComponent) {
    pauseCurrentSlide(alpineComponent);
    alpineComponent.showArrows = false;
}

function updateIndex(alpineComponent, newIndex) {
    alpineComponent.previousIndex = alpineComponent.currentIndex; // Save the previous index before updating
    alpineComponent.currentIndex = newIndex;                      // Update to the new index
}

function getCurrentPlayerId(alpineComponent) {
    return alpineComponent.playerIds[alpineComponent.currentIndex - 1];
}

function getPreviousPlayerId(alpineComponent) {
    return alpineComponent.playerIds[alpineComponent.previousIndex - 1];
}
