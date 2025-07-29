
function getSendMessageUrl(conversationId) {
    return `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}`;
}


function getMarkAsReadUrl(message_id, user_id) {
    return `${siteData.siteUrl}/wp-json/v1/read_receipts/${message_id}/${siteData.userId}`;
}


function getConversationsUrl(cursor, isUpdate) {
    let params = [];
    if (cursor) { params.push(`cursor=${encodeURIComponent(cursor)}`); }
    if (isUpdate) { params.push(`update=true`); }
    let query = params.length > 0 ? `?${params.join('&')}` : '';
    return `${siteData.siteUrl}/wp-json/v1/conversations/${query}`;
}


function getMessagesUrl(conversationId, cursor, isUpdate, isLongPoll) {
    let params = [];
    if (cursor) { params.push(`cursor=${encodeURIComponent(cursor)}`); }
    if (isUpdate) { params.push(`update=true`); }
    if (isLongPoll) { params.push(`lp=true`); }
    let query = params.length > 0 ? `?${params.join('&')}` : '';
    return `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}/${query}`;
}


async function fetchResource(alco, method, url, resourceName = 'resource', inFlightFlag = null, data = null) {
    if (inFlightFlag) { alco[inFlightFlag] = true; }
    var fetchOptions = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': siteData.nonce,
        },
    };
    if (data) { fetchOptions.body = JSON.stringify(data); }

    return await fetch(url, fetchOptions)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status} :: Failed to ${method} ${resourceName}`, { cause: response.status });
        }
        if (inFlightFlag) { alco[inFlightFlag] = false; }
        return response.json();
    })
    .catch(error => {
        if (inFlightFlag) { alco[inFlightFlag] = false; }
        return error;
    });
}
