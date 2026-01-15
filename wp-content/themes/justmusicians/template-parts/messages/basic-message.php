
<div class="min-h-[81px]" x-bind:id="_getMessageElmId(message.conversation_id, message.message_id)"
    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-show="showPaginationMessages" x-cloak
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } _getMessages(message.conversation_id, message.message_id); })"
    <?php } else { ?>
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } })"
    <?php } ?>
>

    <!-- Timestamp -->
    <?php echo get_template_part('template-parts/messages/parts/timestamp', '', []); ?>


     <!-- Message with profile image -->
    <div class="flex items-start gap-2"
        :class="{ 'flex-row-reverse ml-auto': message.is_outgoing, 'flex-row': !message.is_outgoing, }"
    >

        <!-- Profile image -->
        <?php echo get_template_part('template-parts/messages/parts/profile-img', '', []); ?>


        <!-- Message -->
        <div class="w-fit max-w-[75%]"
            :class="{ 'ml-auto': message.is_outgoing }"
            x-data="{ show: false }"
            x-show="show" x-cloak
            x-init="requestAnimationFrame(() => { show = true })"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-6"
            x-transition:enter-end="opacity-100 translate-y-0"
        >

            <!-- Sender name -->
            <?php echo get_template_part('template-parts/messages/parts/sender-name', '', []); ?>


            <!-- Message content -->
            <div class="rounded p-2 text-16 font-thin break-words whitespace-normal"
                :class="{
                    'bg-yellow text-white': message.is_outgoing,
                    'bg-grey-light-50': !message.is_outgoing,
                }"
                x-html="message.content"
            ></div>


        </div>

    </div>

</div>
