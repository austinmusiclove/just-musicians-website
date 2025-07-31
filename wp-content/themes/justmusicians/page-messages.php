<?php

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page
get_header();

$inquiry_data = null;
if (!empty($_GET['iid'])) {
    $inquiry_id = $_GET['iid'];
    $inquiry_data = get_user_inquiry(['post_id' => $inquiry_id]);
    if (is_wp_error($inquiry_data)) { wp_redirect(site_url()); }
}

?>

<div id="sticky-sidebar" class="hidden lg:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar">
        <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => true ]); ?>
    </div>
</div>

<div class="lg:container h-[85vh] lg:h-[69vh]"
    x-data="{
        conversationId: -1,
        conversations: [],
        conversationsMap: {},
        inquiry: <?php if ($inquiry_data != null) { echo clean_arr_for_doublequotes($inquiry_data); } else { echo 'null'; } ?>,
        conversationsView: true,
        messagesView: false,
        getCvInFlight: false,
        getMsgInFlight: false,
        messageInFlight: false,
        showPaginationMessages: true,
        messagePollingInterval: null,
        _sendMessage(conversationId, message)       { sendMessage(this, conversationId, message); },
        _markAsRead(conversationId, messageId)      { markAsRead(this, conversationId, messageId); },
        _markAsUnread(conversationId, messageId)    { markAsUnread(this, conversationId, messageId); },
        _getConversations(cursor)                   { getConversations(this, cursor, this.inquiry ? this.inquiry.inquiry_id : null); },
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
        <div class="flex-col col-span-12 lg:col-span-4 z-0 border-r border-black/20 h-[85vh] lg:h-[69vh] lg:pl-[2rem] xl:pl-0"
            :class="conversationsView ? 'flex' : 'hidden lg:flex'"
        >
            <?php echo get_template_part('template-parts/messages/conversations-menu', '', []); ?>
        </div>

        <!-- Message Board -->
        <div class="pb-4 flex-col lg:col-span-8 h-[85vh] lg:h-[69vh]"
            :class="messagesView ? 'flex' : 'hidden lg:flex'"
        >
            <?php echo get_template_part('template-parts/messages/message-board', '', []); ?>
        </div>


    </div>
</div>

<span class="hidden lg:block">
<?php
get_footer();
?>
</span>


