
async function selectConversation(alco, conversationId) {
    alco.conversationId = conversationId;

    // Get first page of messages if there are none yet
    if (alco.conversationsMap[conversationId].messages.length == 0) {
        await getMessages(alco, conversationId, null);

    // Get newer messages if there are already messages for this conversation
    } else {
        var conversation = alco.conversationsMap[conversationId];
        await getMessages(alco, conversationId, conversation.messages[conversation.messages.length-1].message_id, true);
    }

    // Scroll to latest message
    alco.$nextTick(() => {
        var conversation = alco.conversationsMap[conversationId];
        scrollToElement(alco, getMessageElmId(conversationId, conversation.messages[conversation.messages.length-1].message_id));
    });

    pollMessages(alco, conversationId);
}

async function sendMessage(alco, conversationId, message) {
    var message = await fetchResource(alco, 'messageInFlight', 'POST', getSendMessageUrl(conversationId), 'message', {'content': message});
    if (message) {
        await updateApplicationState(alco, null, { [conversationId]: [message] });

        //update UI
        // Scroll to bottom of message board and clear input
        alco.$nextTick(() => {
            //console.log('scroll: ' + getMessageElmId(conversationId, message.message_id));
            scrollToElement(alco, getMessageElmId(conversationId, message.message_id), 100);
            alco.$refs.messageInput.value = '';
            alco.$refs.messageInput.rows = 1;
            alco.$refs.messageInput.focus();
        });
    }
}

async function getConversations(alco, cursor) {
    var conversations = await fetchResource(alco, 'showCvSpinner', 'GET', getConversationsUrl(cursor), 'conversations');
    if (conversations) { await updateApplicationState(alco, conversations, null); }
}

async function getMessages(alco, conversationId, cursor, getNewer) {
    var messages = await fetchResource(alco, getNewer ? '' : 'showMbSpinner', 'GET', getMessagesUrl(conversationId, cursor, getNewer), 'messages');
    if (messages.length > 0) {
        alco.showPaginationMessages = false; // Hide messages that trigger pagination on x-intersect
        await updateApplicationState(alco, null, { [conversationId]: messages });

        // Update UI
        // scroll to latest new message after all messages have been rendered by alpine
        alco.$nextTick(() => {
            scrollToElement(alco, getMessageElmId(conversationId, alco.conversationsMap[conversationId].messages[messages.length-1].message_id));
        });
    }

    // Show pagination triggering messages and hide spinner
    alco.$nextTick(() => {
        alco.showPaginationMessages = true;
    });
}

async function pollConversations(alco) {
    while (true) {
        await new Promise(resolve => setTimeout(resolve, 5000)); // Wait 5 seconds before polling
        await new Promise(requestAnimationFrame);                // Pause while browser tab is inactive
        try {
            var cursor = alco.conversations[0].latest_message_id
            var conversations = await fetchResource(alco, null, 'GET', getConversationsUrl(cursor, true), 'conversations');
            if (conversations.length > 0) {
                await updateApplicationState(alco, conversations, null);
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    }
}

async function pollMessages(alco, conversationId) {
    stopPollingMessages(alco); // Kill any existing interval first

    alco.messagePollingInterval = setInterval(async () => {
        var conversation = alco.conversationsMap[conversationId];
        await getMessages(alco, conversationId, conversation.messages[conversation.messages.length-1].message_id, true);

        // Scroll to latest message
        alco.$nextTick(() => {
            var conversation = alco.conversationsMap[conversationId];
            scrollToElement(alco, getMessageElmId(conversationId, conversation.messages[conversation.messages.length-1].message_id));
        });
    }, 5000);
}
function stopPollingMessages(alco) {
    if (alco.messagePollingInterval !== null) {
        clearInterval(alco.messagePollingInterval);
        alco.messagePollingInterval = null;
    }
}


const updateApplicationState = createLockedFunction(_updateApplicationState);
async function _updateApplicationState(alco, conversations, messages) {
    //console.log('🔵 [updateApplicationState] Start:', new Date().toISOString());
    //await new Promise(resolve => setTimeout(resolve, 500)); // Simulate a delay

    // make a copy of current state to work with
    var conversationsMapCopy = JSON.parse(JSON.stringify(alco.conversationsMap));

    // Merge in new data; merge messages first to avoide overwriting existing messages
    if (conversations) { conversationsMapCopy = updateConversations(conversationsMapCopy, conversations); }
    if (messages)      { conversationsMapCopy = updateConversationMessages(conversationsMapCopy, messages); }

    // sort conversations
    var allConversations = Object.values(conversationsMapCopy);
    allConversations.sort((a, b) => {
        return new Date(b.latest_message_created_at) - new Date(a.latest_message_created_at);
    });

    // update application state
    alco.conversations = allConversations;
    for (var conversation of allConversations) {
        alco.conversationsMap[conversation.conversation_id] = conversation;
    }

    //console.log('🟢 [updateApplicationState] End:', new Date().toISOString());
}

// add new conversations and replace old with new in the case of duplicates
function updateConversations(currentConversationsMap, newConversationsArray) {
    for (var conversation of newConversationsArray) {
        if (conversation.conversation_id in currentConversationsMap) {
            conversation.messages = currentConversationsMap[conversation.conversation_id].messages; // Do not overwrite messages
        }
        currentConversationsMap[conversation.conversation_id] = conversation;
    }
    return currentConversationsMap;
}

// merge messages them with existing messages and update the conversation meta data
function updateConversationMessages(currentConversationsMap, newMessagesMap) {
    for (var conversationId in newMessagesMap) {
        if (conversationId in currentConversationsMap) {
            currentConversationsMap[conversationId].messages = mergeMessages(conversationId, currentConversationsMap[conversationId].messages, newMessagesMap[conversationId]);
            var conversation = currentConversationsMap[conversationId];
            var latestMessage = conversation.messages[conversation.messages.length-1];
            if (new Date(latestMessage.created_at) > new Date(conversation.latest_message_created_at)) {
                currentConversationsMap[conversationId].latest_message_content    = latestMessage.content;
                currentConversationsMap[conversationId].latest_message_created_at = latestMessage.created_at;
                currentConversationsMap[conversationId].latest_message_is_read    = latestMessage.is_read;
                currentConversationsMap[conversationId].latest_message_id = latestMessage.message_id;
                currentConversationsMap[conversationId].latest_message_sender_id  = latestMessage.sender_id;
            }
        }
    }
    return currentConversationsMap;
}
function mergeMessages(conversationId, currentMessages, newMessages) {

    // Avoid duplicates and take the newer of any duplicates
    var tmpMap = new Map();
    var combinedMessages = [...currentMessages, ...newMessages];
    for (var message of combinedMessages) {
        tmpMap.set(message.message_id, message);
    }

    // Sort messages
    var allMessages = Array.from(tmpMap.values());
    allMessages.sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });

    return allMessages;
}

function getMessageElmId(conversationId, messageId) {
    return 'message-' + conversationId + '-' + messageId;
}

function scrollToElement(alco, id, delay=0, behavior="instant") {
    //console.log('scroll to elm: ' + id);
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


// API
function getSendMessageUrl(conversationId) {
    return `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}`;
}
/*
function getConversationsUrl(cursor, isUpdate) {
    let url = `${siteData.siteUrl}/wp-json/v1/conversations/`;
    if (cursor != null) { url += `?cursor=${encodeURIComponent(cursor)}`; }
    return url;
}
*/
function getConversationsUrl(cursor, isUpdate) {
    let params = [];
    if (cursor != null) { params.push(`cursor=${encodeURIComponent(cursor)}`); }
    if (isUpdate) { params.push(`update=true`); }
    let query = params.length > 0 ? `?${params.join('&')}` : '';
    return `${siteData.siteUrl}/wp-json/v1/conversations/${query}`;
}

function getMessagesUrl(conversationId, cursor, isUpdate) {
    let params = [];
    if (cursor != null) { params.push(`cursor=${encodeURIComponent(cursor)}`); }
    if (isUpdate) { params.push(`update=true`); }
    let query = params.length > 0 ? `?${params.join('&')}` : '';
    return `${siteData.siteUrl}/wp-json/v1/messages/${conversationId}/${query}`;
}
async function fetchResource(alco, spinner = null, method, url, resourceName = 'resource', data = null) {
    if (spinner) { alco[spinner] = true; }
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
            throw new Error(`HTTP error ${response.status} :: Failed to ${method} ${resourceName}`);
        }
        if (spinner) { alco[spinner] = false; }
        return response.json();
    })
    .catch(error => {
        if (spinner) { alco[spinner] = false; }
        alco.$dispatch('error-toast', { message: error });
    });
}
