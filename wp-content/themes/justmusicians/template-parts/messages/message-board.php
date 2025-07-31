

<!-- Conversation Header -->
<div class="my-8 pt-4 md:pt-20">

    <!-- Back button -->
    <span class="flex opacity-50 lg:hidden cursor-pointer" x-on:click="messagesView = false; conversationsView = true;">
        <img class="mb-2 ml-[-8px] h-6 opacity-80 text-grey" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left.svg'; ?>" />
        <span class="text-18 hover:underline" >Back</span>
    </span>

    <!-- Conversation Title -->
    <h2 class="font-bold text-22" x-text="(conversationId > 0) ? conversationsMap[conversationId].title : ''"></h2>

</div>


<!-- Message board spinner -->
<div class="flex items-center justify-center" x-show="getMsgInFlight" x-cloak>
    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
</div>

<!-- Display messages -->
<?php echo get_template_part('template-parts/messages/messages-display', '', []); ?>

<!-- Message Input Area -->
<?php echo get_template_part('template-parts/messages/message-input', '', []); ?>
