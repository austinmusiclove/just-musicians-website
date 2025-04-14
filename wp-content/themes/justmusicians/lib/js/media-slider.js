function pausePreviousSlide(alpineComponent) {
    if (alpineComponent.previousIndex > 0) {
        alpineComponent.$dispatch('pause-youtube-player', {'playerId': alpineComponent.playerIds[alpineComponent.previousIndex - 1]});
    }
}

function pauseCurrentSlide(alpineComponent) {
    if (alpineComponent.currentIndex > 0) {
        alpineComponent.$dispatch('pause-youtube-player', {'playerId': alpineComponent.playerIds[alpineComponent.currentIndex - 1]});
    }
}

function playCurrentSlide(alpineComponent) {
    if (alpineComponent.currentIndex > 0) {
        alpineComponent.$dispatch('play-youtube-player', {'playerId': alpineComponent.playerIds[alpineComponent.currentIndex - 1]});
    }
}

function toggleMuteAllVideos(alpineComponent) {
    alpineComponent.$dispatch('mute-youtube-players');
}

function isPaused(alpineComponent) {
    return alpineComponent.currentIndex > 0 && alpineComponent.players[alpineComponent.playerIds[alpineComponent.currentIndex - 1]].isPaused;
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

function initSliderYoutubePlayers(alpineComponent) {
    alpineComponent.playerIds.forEach((playerId, index) => {
        if (playerId) {
            alpineComponent.$dispatch('init-youtube-player', {
                'playerId': playerId,
                'videoId': alpineComponent.videoIds[index]
            });
        }
    });
}
