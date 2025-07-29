
<div id="conversation-container" class="flex-1 border-t border-black/20 w-full overflow-y-scroll"
    x-data="{
        openMenu: null,
        toggleOpenMenu(conversationId) {
            if (this.openMenu == conversationId) {
                this.openMenu = null;
            } else {
                this.openMenu = conversationId;
            }
        },
    }"
>


    <template x-for="(conversation, index) in conversations" :key="index">
        <span>

            <!-- Display Conversation  -->
            <template x-if="index < conversations.length-1">
                <?php echo get_template_part('template-parts/messages/conversation', '', ['is_last' => false]); ?>
            </template>

            <!-- Display last conversation: distinct from other conversations for pagination purposes -->
            <template x-if="index == conversations.length-1">
                <?php echo get_template_part('template-parts/messages/conversation', '', ['is_last' => true]); ?>
            </template>

        </span>
    </template>


</div>
