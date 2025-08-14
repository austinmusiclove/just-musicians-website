
const updateApplicationState = createLockedFunction(_updateApplicationState);
async function _updateApplicationState(alco, conversations, messages) {
    // make a copy of current state to work with
    var conversationsMapCopy = JSON.parse(JSON.stringify(alco.conversationsMap));

    // Merge in new data; merge messages first to avoide overwriting existing messages
    if (conversations) { conversationsMapCopy = updateConversations(conversationsMapCopy, conversations); }
    if (messages)      { conversationsMapCopy = updateConversationMessages(alco, conversationsMapCopy, messages); }

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
function updateConversationMessages(alco, currentConversationsMap, newMessagesMap) {
    for (var conversationId in newMessagesMap) {
        if (conversationId in currentConversationsMap) {
            currentConversationsMap[conversationId].messages = mergeMessages(conversationId, currentConversationsMap[conversationId].messages, newMessagesMap[conversationId]);
            var conversation = currentConversationsMap[conversationId];
            var latestMessage = conversation.messages[conversation.messages.length-1];
            if (new Date(latestMessage.created_at) >= new Date(conversation.latest_message_created_at)) {
                currentConversationsMap[conversationId].latest_message_content    = latestMessage.content.replace('<br />', '');
                currentConversationsMap[conversationId].latest_message_created_at = latestMessage.created_at;
                currentConversationsMap[conversationId].latest_message_is_read    = latestMessage.is_read;
                currentConversationsMap[conversationId].latest_message_id         = latestMessage.message_id;
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
