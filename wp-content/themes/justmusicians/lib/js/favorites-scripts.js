
// Handles front end favorites buttons interactions
// Server interaction handled by htmx


function showAddFavoriteButton(alpineComponent, postId) {
    return !alpineComponent.saved_listings.includes(postId);
}

function showRemoveFavoriteButton(alpineComponent, postId) {
    return alpineComponent.saved_listings.includes(postId);
}

function addToFavorites(alpineComponent, postId) {
    if (!alpineComponent.saved_listings.includes(postId)) {
        alpineComponent.saved_listings.push(postId);
    }
}

function removeFromFavorites(alpineComponent, postId) {
    alpineComponent.saved_listings = alpineComponent.saved_listings.filter(id => id !== postId);
}
