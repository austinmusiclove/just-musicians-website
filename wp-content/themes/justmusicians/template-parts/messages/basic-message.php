
<div class="min-h-[81px]" x-bind:id="_getMessageElmId(message.conversation_id, message.message_id)"
    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-show="showPaginationMessages" x-cloak
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } _getMessages(message.conversation_id, message.message_id); })"
    <?php } else { ?>
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } })"
    <?php } ?>
>

    <!-- Timestamp -->
    <template x-if="message.created_at">
        <div class="text-center text-grey text-14 my-2" x-text="new Date(message.created_at.replace(' ', 'T') + 'Z').toLocaleString()"></div>
    </template>


     <!-- Message with profile image -->
    <div class="flex items-start gap-2"
        :class="{ 'flex-row-reverse ml-auto': message.is_outgoing, 'flex-row': !message.is_outgoing, }"
    >

        <!-- Profile image -->
        <a href="<?php echo site_url('/account'); ?>" x-show="message.is_outgoing" x-cloak>
            <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">
        </a>
        <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url" x-show="!message.is_outgoing" x-cloak>


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
            <div class="text-14"
                :class="{ 'text-right': message.is_outgoing, 'text-left': !message.is_outgoing }"
                x-text="message.sender_name"
            ></div>


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
