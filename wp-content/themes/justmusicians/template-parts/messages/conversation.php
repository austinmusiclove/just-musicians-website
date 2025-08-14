<div class="pr-3 py-4 border-b border-black/20 hover:bg-yellow-10"
    :class="{ 'bg-yellow-10': conversationId == conversation.conversation_id }"
    x-on:click="_selectConversation(conversation.conversation_id); messagesView = true; conversationsView = false;"

    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-intersect.once="_getConversations(conversation.latest_message_id)"
    <?php } ?>
>

    <!-- Conversation title -->
    <div class="flex items-center justify-between mb-2">

        <!-- Title -->
        <h3 class="text-18"
            :class="{ 'font-bold': !conversation.latest_message_is_read, 'font-semibold': conversation.latest_message_is_read }"
            x-text="conversation.title">
        </h3>

        <!-- Popup Menu -->
        <div class="relative">
            <button class="ml-2 p-1 rounded hover:bg-yellow-10 focus:outline-none focus:ring" title="Options" x-on:click.stop="toggleOpenMenu(conversation.conversation_id)">
                <svg class="w-5 h-5 text-grey" fill="currentColor" viewBox="0 0 20 20">
                    <circle cx="10" cy="4" r="1.5" />
                    <circle cx="10" cy="10" r="1.5" />
                    <circle cx="10" cy="16" r="1.5" />
                </svg>
            </button>

            <!-- Popup -->
            <div class="absolute right-0 mt-2 w-40 bg-white border border-grey rounded shadow z-10"
                x-show="openMenu == conversation.conversation_id" x-cloak
                x-on:click.away="openMenu = null"
                x-transition
            >

                <!-- Mark as unread -->
                <template x-if="conversation.latest_message_is_read">
                    <button class="w-full text-left px-4 py-2 text-sm hover:bg-yellow-10"
                        x-on:click.stop="_markAsUnread(conversation.conversation_id, conversation.latest_message_id); openMenu = false"
                    >Mark as Unread</button>
                </template>
                <!-- Mark as read -->
                <template x-if="!conversation.latest_message_is_read">
                    <button class="w-full text-left px-4 py-2 text-sm hover:bg-yellow-10"
                        x-on:click.stop="_markAsRead(conversation.conversation_id, conversation.latest_message_id); openMenu = false"
                    >Mark as Read</button>
                </template>

            </div>
        </div>
    </div>


    <!-- Latest message preview -->
    <p class="w-full text-14"
        :class="{ 'font-bold': !conversation.latest_message_is_read }"
    >
        <span class="flex items-center">
            <span class="mr-2" x-show="!conversation.latest_message_is_read" x-cloak>
                <svg width="8" height="8" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="4" cy="4" r="4" fill="blue" />
                </svg>
            </span>
            <span class="truncate" x-text="conversation.latest_message_content"></span>
        </span>
    </p>
</div>
