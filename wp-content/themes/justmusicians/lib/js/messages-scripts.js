
async function getConversations(alco, cursor) {
    var conversations = await fetchResource(alco, 'GET', getConversationsUrl(cursor, false), 'conversations', 'showCvSpinner', true);
    if (conversations) { await updateApplicationState(alco, conversations, null); }
}

async function getMessages(alco, conversationId, cursor, isUpdate, isLongPoll) {
    var messages = await fetchResource(alco, 'GET', getMessagesUrl(conversationId, cursor, isUpdate, isLongPoll), 'messages', isUpdate ? '' : 'showMbSpinner', !isUpdate);
    if (messages.length > 0) {
        alco.showPaginationMessages = false; // Hide messages that trigger pagination on x-intersect
        await updateApplicationState(alco, null, { [conversationId]: messages });

        // scroll to latest new message after all messages have been rendered by alpine
        await alco.$nextTick(async () => {
            scrollToElement(alco, getMessageElmId(conversationId, alco.conversationsMap[conversationId].messages[messages.length-1].message_id));
            alco.showPaginationMessages = true;
        });
    }
}

async function sendMessage(alco, conversationId, message) {
    var message = await fetchResource(alco, 'POST', getSendMessageUrl(conversationId), 'message', 'messageInFlight', true, {'content': message});
    if (message) {
        await updateApplicationState(alco, null, { [conversationId]: [message] });

        // Scroll to bottom of message board and clear input
        alco.$nextTick(() => {
            scrollToElement(alco, getMessageElmId(conversationId, message.message_id));
            alco.$refs.messageInput.value = '';
            alco.$refs.messageInput.rows = 1;
            alco.$refs.messageInput.focus();
        });
    }
}

async function markAsRead(alco, conversationId, messageId) {
    var message = findMessage(alco, conversationId, messageId);

    // if unread, mark as read
    if (!message || (message && !message.is_read)) {
        var result = await fetchResource(alco, 'POST', getMarkAsReadUrl(messageId), 'message', null, false, {'message_id': messageId});
        if (result) {
            if (message) {
                message.is_read = true;
                await updateApplicationState(alco, null, { [conversationId]: [message] });
            } else {
                var conversation = alco.conversationsMap[conversationId];
                conversation.latest_message_is_read = true;
                await updateApplicationState(alco, [conversation], null);
            }
        }
    }
}
async function markAsUnread(alco, conversationId, messageId) {
    var message = findMessage(alco, conversationId, messageId);

    // if unread, mark as read
    if (!message || (message && message.is_read)) {
        var result = await fetchResource(alco, 'DELETE', getMarkAsReadUrl(messageId), 'message', null, false, {'message_id': messageId});
        if (result) {
            if (message) {
                message.is_read = false;
                await updateApplicationState(alco, null, { [conversationId]: [message] });
            } else {
                var conversation = alco.conversationsMap[conversationId];
                conversation.latest_message_is_read = false;
                await updateApplicationState(alco, [conversation], null);
            }
        }
    }
}

async function pollConversations(alco) {
    shortPollConversations(alco);
}
async function shortPollConversations(alco) {
    while (true) {
        await new Promise(resolve => setTimeout(resolve, 5000)); // Wait 5 seconds before polling
        await new Promise(requestAnimationFrame);                // Pause while browser tab is inactive
        try {
            var cursor = alco.conversations[0].latest_message_id
            var conversations = await fetchResource(alco, 'GET', getConversationsUrl(cursor, true), 'conversations', null, false);
            if (conversations.length > 0) {
                await updateApplicationState(alco, conversations, null);
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    }
}
async function shortPollMessages(alco, conversationId) {
    stopPollingMessages(alco); // Kill any existing interval first

    alco.messagePollingInterval = setInterval(async () => {
        // Pause while browser tab is inactive
        await new Promise(requestAnimationFrame);

        // Get messages
        var cursor = getLatestMessage(alco, conversationId).message_id;
        await getMessages(alco, conversationId, cursor, true, false);
    }, 5000);
}
function stopPollingMessages(alco) {
    if (alco.messagePollingInterval !== null) {
        clearInterval(alco.messagePollingInterval);
        alco.messagePollingInterval = null;
    }
}
// Not used because of server capabilities
/*
async function longPollMessages(alco, conversationId) {
    while (alco.conversationId == conversationId) {
        var cursor = getLatestMessage(alco, conversationId).message_id;
        await getMessages(alco, conversationId, cursor, true, true);
    }
}
*/

async function selectConversation(alco, conversationId) {
    alco.showPaginationMessages = false; // hide these when switching because the container is a different height before the switch and there could be an intersection of a pagination element right on the switch
    alco.conversationId = conversationId; // Set active conversation

    // Get first page of messages if there are none yet; else get only newer messages
    if (alco.conversationsMap[conversationId].messages.length == 0) {
        await getMessages(alco, conversationId);
    } else {
        var cursor = getLatestMessage(alco, conversationId).message_id;
        await getMessages(alco, conversationId, cursor, true, false);
    }

    // Scroll to latest message
    alco.$nextTick( async () => {
        alco.showPaginationMessages = true;
        scrollToElement(alco, getMessageElmId(conversationId, getLatestMessage(alco, conversationId).message_id));

        // Start polling for new messages on the active conversation
        //shortPollMessages(alco, conversationId);
    });
}



function scrollToElement(alco, id, delay=0, behavior="instant") {
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
    console.log('scroll to retry: ' + id);
    function attemptScroll(remainingAttempts) {
        console.log('try: ' + remainingAttempts);
        var elm = document.getElementById(id);
        if (elm) {

            // Element exists and is measurable (has layout)
            if (elm.offsetHeight > 80 || remainingAttempts <= 0) {
                console.log('exists');
                elm.scrollIntoView({ behavior });

            // Wait a bit and retry
            } else {
                console.log('retry');
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
function getMessageElmId(conversationId, messageId) {
    return 'message-' + conversationId + '-' + messageId;
}

function getLatestMessage(alco, conversationId) {
    var conversation = alco.conversationsMap[conversationId];
    return conversation.messages[conversation.messages.length-1];
}

function findMessage(alco, conversationId, messageId) {
    var messages = alco.conversationsMap[conversationId].messages;
    return messages.find(msg => msg.message_id == messageId);

}
