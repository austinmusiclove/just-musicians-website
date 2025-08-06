
async function getConversations(alco, cursor, inquiryId) {
    var conversations = await fetchResource(alco, 'GET', getConversationsUrl(cursor, false, inquiryId), 'conversations', 'getCvInFlight');
    if (conversations instanceof Error) {
        alco.$dispatch('error-toast', { message: conversations.message });
    } else if (conversations) {
        await updateApplicationState(alco, conversations, null);
    }
}

async function getMessages(alco, conversationId, cursor, isUpdate, isLongPoll) {
    var messages = await fetchResource(alco, 'GET', getMessagesUrl(conversationId, cursor, isUpdate, isLongPoll), 'messages', isUpdate ? '' : 'getMsgInFlight');
    if (messages instanceof Error && !isUpdate) {
        alco.$dispatch('error-toast', { message: messages.message });
        return messages;
    } else if (messages.length > 0) {
        alco.showPaginationMessages = false; // Hide messages that trigger pagination on x-intersect
        await updateApplicationState(alco, null, { [conversationId]: messages });

        // scroll to latest new message after all messages have been rendered by alpine
        await alco.$nextTick(async () => {
            scrollToElement(alco, getMessageElmId(conversationId, alco.conversationsMap[conversationId].messages[messages.length-1].message_id));
            alco.showPaginationMessages = true;
        });
    }
    return messages;
}

async function sendMessage(alco, conversationId, message) {
    var newMessage = await fetchResource(alco, 'POST', getSendMessageUrl(conversationId), 'message', 'messageInFlight', {'content': message});
    if (newMessage instanceof Error ) {
        alco.$dispatch('error-toast', { message: newMessage.message });
    } else if (newMessage) {
        await updateApplicationState(alco, null, { [conversationId]: [newMessage] });

        // Scroll to bottom of message board and clear input
        alco.$nextTick(() => {
            scrollToElement(alco, getMessageElmId(conversationId, newMessage.message_id));
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
        var result = await fetchResource(alco, 'POST', getMarkAsReadUrl(messageId), 'message', null, {'message_id': messageId});
        if (!(result instanceof Error)) {
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
        var result = await fetchResource(alco, 'DELETE', getMarkAsReadUrl(messageId), 'message', null, {'message_id': messageId});
        if (!(result instanceof Error)) {
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
        var cursor = alco.conversations.length > 0 ? alco.conversations[0].latest_message_id : null;
        var isUpdate = alco.conversations.length > 0;
        var inquiryId = alco.inquiry ? alco.inquiry.inquiry_id : null;
        var conversations = await fetchResource(alco, 'GET', getConversationsUrl(cursor, isUpdate, inquiryId), 'conversations', null);
        if (conversations instanceof Error && conversations.cause == 403) {
            break;
        } else if (conversations.length > 0) {
            await updateApplicationState(alco, conversations, null);
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
        var result = await getMessages(alco, conversationId, cursor, true, false);
        if (result instanceof Error && result.cause == 403) { stopPollingMessages(alco); }
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
    var messages = null;

    // Get first page of messages if there are none yet; else get only newer messages
    if (alco.conversationsMap[conversationId].messages.length == 0) {
        messages = await getMessages(alco, conversationId);
    } else {
        var cursor = getLatestMessage(alco, conversationId).message_id;
        messages = await getMessages(alco, conversationId, cursor, true, false);
    }

    // Scroll to latest message
    if (!(messages instanceof Error)) {
        alco.$nextTick( async () => {
            alco.showPaginationMessages = true;
            scrollToElement(alco, getMessageElmId(conversationId, getLatestMessage(alco, conversationId).message_id));

            // Start polling for new messages on the active conversation
            shortPollMessages(alco, conversationId);
        });
    }
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
