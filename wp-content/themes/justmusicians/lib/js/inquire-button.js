// Handles front end inquire buttons interactions
// Server interaction handled by htmx

function showRequestProposalButton(alco, eventId, listingId) {
    return !alco.$data.eventsMap[eventId].listings.includes(listingId);
}

function showListingInEvent(alco, eventId, listingId) {
    return alco.$data.eventsMap[eventId].listings.includes(listingId);
}

function addListingToEvent(alco, eventId, listingId) {
    var eventObj = alco.$data.eventsMap[eventId];
    if (eventObj && !eventObj.listings.includes(listingId)) {
        alco.$data.eventsMap[eventId].listings.push(listingId);
    }
}

function addEvent(alco, post_id, event_name, listings, permalink) {
    alco.$data.eventsMap[post_id] = {
        'post_id':    post_id,
        'event_name': event_name,
        'listings':   listings,
        'permalink':  permalink,
    };
}

function resetEventsMenu(alco, listingId) {
    alco.showEventsMenu = false;
    alco.eventSearchQuery = '';
    alco.$refs['eventsList' + listingId].scrollTop = 0;
}

function getSortedEvents(alco) {
    return Object.values(alco.$data.eventsMap).sort((a, b) => b.post_id - a.post_id);
}
