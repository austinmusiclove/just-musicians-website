// Handles front end collections buttons interactions
// Server interaction handled by htmx

function showEmptyFavoriteButton(alpineComponent, listingId) {
    return !Object.values(alpineComponent.$data.collections).some(collection => collection.listings.includes(listingId));
}

function showFilledFavoriteButton(alpineComponent, listingId) {
    return Object.values(alpineComponent.$data.collections).some(collection => collection.listings.includes(listingId));
}

function showEmptyCollectionButton(alpineComponent, collectionId, listingId) {
    return !alpineComponent.$data.collections[collectionId].listings.includes(listingId);
}

function showFilledCollectionButton(alpineComponent, collectionId, listingId) {
    return alpineComponent.$data.collections[collectionId].listings.includes(listingId);
}

// if listing in any collection other than favorites open the popup so that user can manage collections
// else remove from favorites
function handleFilledFavoriteButtonClick(alpineComponent, listingId) {
    if (Object.values(alpineComponent.$data.collections).filter(collection => collection.post_id !== 0).some(collection => collection.listings.includes(listingId))) {
        alpineComponent.showCollectionsPopup = true;
    } else {
        alpineComponent.$dispatch('remove-from-favorites');
    }
}

function addToCollection(alpineComponent, collectionId, listingId) {
    var collection = alpineComponent.$data.collections[collectionId];
    if (collection && !collection.listings.includes(listingId)) {
        alpineComponent.$data.collections[collectionId].listings.push(listingId);
    }
}

function removeFromCollection(alpineComponent, collectionId, listingId) {
    var collection = alpineComponent.$data.collections[collectionId];
    if (collection) {
        alpineComponent.$data.collections[collectionId].listings = collection.listings.filter(id => id !== listingId);
    }
}

function addCollection(alpineComponent, post_id, name, listings, permalink) {
    alpineComponent.$data.collections[post_id] = {
        'post_id':   post_id,
        'name':      name,
        'listings':  listings,
        'permalink': permalink,
    };
}

function resetCollectionsPopup(alpineComponent, listingId) {
    alpineComponent.showCollectionsPopup = false;
    alpineComponent.showCreateCollectionInput = false;
    alpineComponent.collectionSearchQuery = '';
    alpineComponent.$refs['newCollectionInput' + listingId].value = '';
    alpineComponent.$refs['collectionsList' + listingId].scrollTop = 0;
}
