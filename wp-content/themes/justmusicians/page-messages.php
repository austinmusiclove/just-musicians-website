<?php

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page
get_header();

?>

<div id="sticky-sidebar" class="hidden lg:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar">
        <?php echo get_template_part('template-parts/account/sidebar', '', [
            'collapsible' => true
        ]); ?>
    </div>
</div>

<div class="lg:container h-[69vh]"
    x-data="{
        conversationId: -1,
        conversations: [],
        conversationsMap: {},
        showCvSpinner: false,
        showMbSpinner: false,
        messageInFlight: false,
        messagePollingInterval: null,
        showPaginationMessages: true,
        _sendMessage(conversationId, message)       { sendMessage(this, conversationId, message); },
        _markAsRead(conversationId, messageId)      { markAsRead(this, conversationId, messageId); },
        _markAsUnread(conversationId, messageId)    { markAsUnread(this, conversationId, messageId); },
        _getConversations(cursor)                   { getConversations(this, cursor); },
        _getMessages(conversationId, cursor)        { getMessages(this, conversationId, cursor); },
        _pollConversations()                        { pollConversations(this); },
        _pollMessages(conversationId, cursor)       { pollMessages(this, conversationId, cursor); },
        _selectConversation(conersationId)          { selectConversation(this, conersationId); },
        _getMessageElmId(conversationId, messageId) { return getMessageElmId(conversationId, messageId); },
    }"
    x-ref="mainContainer"
    x-init="_getConversations(null); _pollConversations();"
>

    <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">

        <!-- Conversations Menu -->
        <div class="flex flex-col col-span-12 lg:col-span-3 z-0 border-r border-black/20 h-[69vh]">

            <header class="pt-4 sm:pt-20 xl:pt-32 mb-4 sm:mb-12 gap-12 sm:gap-4 flex flex-col-reverse sm:flex-row justify-between sm:items-center">
                <h1 class="font-bold text-22 sm:text-25">Conversations</h1>
            </header>

            <!-- Show Conversations -->
            <?php echo get_template_part('template-parts/messages/conversations', '', []); ?>

            <!-- Spinner -->
            <div class="flex items-center justify-center" x-show="showCvSpinner" x-cloak>
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
            </div>

        </div>

        <!-- Message Board -->
        <div class="hidden pb-4 lg:flex flex-col lg:col-span-9 h-[69vh]">

            <!-- Conversation Title -->
            <div><h2 class="my-8 font-bold text-22" x-text="(conversationId > 0) ? conversationsMap[conversationId].title : ''"></h2></div>

            <!-- Message board spinner -->
            <div class="flex items-center justify-center" x-show="showMbSpinner" x-cloak>
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
            </div>

            <!-- Display messages -->
            <?php echo get_template_part('template-parts/messages/message-board', '', []); ?>

            <!-- Message Input Area -->
            <?php echo get_template_part('template-parts/messages/message-input', '', []); ?>

        </div>


    </div>
</div>

<?php
get_footer();


