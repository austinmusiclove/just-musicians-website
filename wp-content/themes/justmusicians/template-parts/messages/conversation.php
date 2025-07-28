
<div class="px-3 py-4 border-b border-black/20 hover:bg-yellow-10"
    :class="{ 'bg-yellow-10': conversationId == conversation.conversation_id }"
    x-on:click="_selectConversation(conversation.conversation_id);"

    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-intersect.once="_getConversations(conversation.latest_message_id)"
    <?php } ?>
>

    <!-- Conversation title -->
    <h3 class="text-18 mb-2"
        :class="{ 'font-bold': conversation.latest_message_is_unread, 'font-semibold': !conversation.latest_message_is_unread }"
        x-text="conversation.title"
    ></h3>

    <!-- Latest message preview -->
    <p class="w-full text-14 truncate"
        :class="{ 'font-bold': conversation.latest_message_is_unread }"
        x-text="conversation.latest_message_content"
    ></p>

</div>
