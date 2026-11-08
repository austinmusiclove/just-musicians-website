
<div class="flex-1 overflow-y-auto w-full"
    x-show="!getCvInFlight || conversations.length > 0" x-cloak
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

    <!-- No conversations -->
    <template x-if="!getCvInFlight && conversations.length == 0">
        <div class="my-4">No Conversations yet</div>
    </template>

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
