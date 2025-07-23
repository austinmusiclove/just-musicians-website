
<div class="px-3 py-4 border-b border-black/20 hover:bg-yellow-10"
    :class="{ 'bg-yellow-10': conversation_id == '<?php echo $args['conversation_id']; ?>' }"
    x-on:click="_selectConversation('<?php echo $args['conversation_id']; ?>', '<?php echo $args['title']; ?>');"
    <?php if ($args['is_last']) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/conversations/?cursor=' . $args['conversation_id']); ?>"
        hx-trigger="intersect"
        hx-target="#conversation-container"
        hx-swap="beforeend"
        hx-indicator="#cv-spinner"
    <?php } ?>
>

    <!-- Conversation title -->
    <h3 class="text-18 mb-2 <?php if ($args['is_unread']) { echo 'font-bold'; } else { echo 'font-semibold'; } ?>">
        <?php echo $args['title']; ?>
    </h3>

    <!-- Latest message preview -->
    <p class="w-full text-14 truncate <?php if ($args['is_unread']) { echo 'font-bold'; } ?>">
        <?php echo $args['message_content']; ?>
    </p>

</div>
