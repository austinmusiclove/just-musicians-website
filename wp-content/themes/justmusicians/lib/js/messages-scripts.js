
async function sendMessage(alco, conversationId, message) {
    alco.messageSending = true;
    let url = `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}`;              // Build resource url
    var message = await fetchResource(alco, 'POST', url, 'message', {'content': message});      // Get conversations from server

    if (message) {
        console.log('message');
        //alco.conversationsMap[conversationId].messages.push(message);
        alco.conversationsMap[conversationId].messages = mergeMessages(alco, conversationId, [message]); // Update application state
        alco.$nextTick(() => {
            console.log('scroll: ' + getMessageElmId(conversationId, message.message_id));
            scrollToElement(alco, getMessageElmId(conversationId, message.message_id), 100);
            alco.$refs.messageInput.value = '';
            alco.$refs.messageInput.rows = 1;
            alco.$refs.messageInput.focus();
        });
    }
    alco.messageSending = false;
}

async function getConversations(alco, cursor) {
    alco.showCvSpinner = true;                                                  // Show spinner
    let url = `${siteData.siteUrl}/wp-json/v1/conversations/`;                  // Build resource url
    if (cursor != null) { url += `?cursor=${encodeURIComponent(cursor)}`; }     // Add cursor to url if exists
    var conversations = await fetchResource(alco, 'GET', url, 'conversations'); // Get conversations from server
    if (conversations) { updateConversations(alco, conversations); }            // Update application state
    alco.showCvSpinner = false;                                                 // Hide spinner
}

async function getMessages(alco, conversationId, cursor) {
    alco.showMbSpinner = true;                                              // Show spinner
    let url = `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}/`; // Build resource url
    if (cursor != null) { url += `?cursor=${encodeURIComponent(cursor)}`; } // Add cursor to url if exists
    var messages = await fetchResource(alco, 'GET', url, 'messages');                // Get messages from server

    if (messages.length > 0) {
        alco.showPaginationMessages = false; // Hide messages that trigger pagination on x-intersect
        alco.conversationsMap[conversationId].messages = mergeMessages(alco, conversationId, messages); // Update application state

        // scroll to latest new message after all messages have been rendered by alpine
        alco.$nextTick(() => {
            scrollToElement(alco, getMessageElmId(conversationId, alco.conversationsMap[conversationId].messages[messages.length-1].message_id));
        });
    }

    // Show pagination triggering messages and hide spinner
    alco.$nextTick(() => {
        alco.showPaginationMessages = true;
        alco.showMbSpinner = false;                                                                                     // Hide spinner
    });
}

function pollConversations(alco) {
}
function pollMessages(alco, conversationId, cursor) {
}

function updateConversations(alco, newConversations) {
    // Merge and sort new and old conversations making sure there are no duplicates and replacing old ones with the updated ones
    var tmpMap = new Map();
    var combinedConversations = [...alco.conversations, ...newConversations];
    for (var convo of combinedConversations) {
        tmpMap.set(convo.conversation_id, convo);
    }
    var allConversations = Array.from(tmpMap.values());
    allConversations.sort((a, b) => {
        return new Date(b.latest_message_created_at) - new Date(a.latest_message_created_at);
    });

    // Update application state
    alco.conversations = allConversations;
    for (var conversation of allConversations) {
        conversation.messages = mergeMessages(alco, conversation.conversation_id, conversation.messages);
        alco.conversationsMap[conversation.conversation_id] = conversation;
    }
}

function mergeMessages(alco, conversationId, newMessages) {
    // Merge and sort new and old messages for a conversation making sure there are no duplicates and replacing old ones with the updated ones
    var combinedMessages = [...newMessages];
    if (conversationId in alco.conversationsMap) {
        combinedMessages = [...alco.conversationsMap[conversationId].messages, ...newMessages];
    }
    var tmpMap = new Map();
    for (var message of combinedMessages) {
        tmpMap.set(message.message_id, message);
    }
    var allMessages = Array.from(tmpMap.values());
    allMessages.sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });

    return allMessages;
}

function selectConversation(alco, conversationId, conversation_title) {
    alco.conversationId = conversationId;

    // Get messages if there are none yet
    if (alco.conversationsMap[conversationId].messages.length == 0) {
        getMessages(alco, conversationId, null);

    // Scroll to latest message
    } else {
        alco.$nextTick(() => {
            var conversation = alco.conversationsMap[conversationId];
            scrollToElement(alco, getMessageElmId(conversationId, conversation.messages[conversation.messages.length-1].message_id));
        });
    }

    // Start polling messages for this conversation
}

function getMessageElmId(conversationId, messageId) {
    return 'message-' + conversationId + '-' + messageId;
}

function scrollToElement(alco, id, delay=0, behavior="instant") {
    console.log('scroll to elm: ' + id);
    requestAnimationFrame(() => {
        var elm = document.getElementById(id);
        if (elm && delay) {
            setTimeout(() => elm.scrollIntoView({behavior}), delay);
        } else if (elm) {
            elm.scrollIntoView({behavior});
        }
    });
}
/*
function scrollToElementRetry(alco, id, behavior = "instant", retry = 5, delay = 50) {
    function attemptScroll(remainingAttempts) {
        const elm = document.getElementById(id);

        if (elm) {
            // Element exists and is measurable (has layout)
            if (elm.offsetHeight > 0 || remainingAttempts <= 0) {
                elm.scrollIntoView({ behavior });
            } else {
                // Wait a bit and retry
                setTimeout(() => attemptScroll(remainingAttempts - 1), delay);
            }
        } else if (remainingAttempts > 0) {
            setTimeout(() => attemptScroll(remainingAttempts - 1), delay);
        }
    }

    // Start the attempt
    requestAnimationFrame(() => attemptScroll(retry));
}
*/


async function fetchResource(alco, method, url, resourceName = 'resource', data = null) {
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
            throw new Error(`HTTP error ${response.status} :: Failed to post ${resourceName}`);
        }
        return response.json();
    })
    .catch(error => {
        alco.$dispatch('error-toast', { message: error });
    });
}
