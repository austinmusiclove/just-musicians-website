<div class="min-h-[81px]" x-bind:id="_getMessageElmId(message.conversation_id, message.message_id)"
    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-show="showPaginationMessages" x-cloak
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } _getMessages(message.conversation_id, message.message_id); })"
    <?php } else { ?>
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } })"
    <?php } ?>
    x-data="{
        _showDate(inquiry)   { return showDate(inquiry); },
        _showTime(inquiry)   { return showTime(inquiry); },
        _showBudget(inquiry) { return showBudget(inquiry); },
    }"
>

    <!-- Timestamp -->
    <?php echo get_template_part('template-parts/messages/parts/timestamp', '', []); ?>


     <!-- Message Wrapper -->
    <div class="flex items-start gap-2"
        :class="{ 'flex-row-reverse ml-auto': message.is_outgoing, 'flex-row': !message.is_outgoing }"
    >

        <!-- Profile image -->
        <?php echo get_template_part('template-parts/messages/parts/profile-img', '', []); ?>

        <div>

            <!-- Sender name -->
            <?php echo get_template_part('template-parts/messages/parts/sender-name', '', []); ?>

            <!-- Inquiry message block -->
            <div class="mb-8 sidebar-module border border-black/40 rounded overflow-hidden bg-white sm:min-w-[320px] max-w-[320px]"
                :class="{ 'ml-auto': message.is_outgoing, 'opacity-50': message.inquiry.expired }"
            >

                <!-- Heading  -->
                <h3 class="bg-yellow text-20 text-white py-2 px-3">New Inquiry</h3>

                <div class="p-4 flex flex-col gap-2" x-show="!message.inquiry.expired">


                    <!-- Details paragraph -->
                    <h3 class="text-18 font-bold py-2" x-html="message.inquiry.subject"></h3>
                    <div class="text-16" x-html="message.inquiry.details"></div>

                    <!-- Details with icons -->
                    <div class="w-full bg-black/20 h-px my-4"></div>
                    <?php echo get_template_part('template-parts/messages/parts/inquiry-message/icon-details', '', []); ?>

                    <!-- Buyer info -->
                    <div class="w-full bg-black/20 h-px my-4"></div>
                    <?php echo get_template_part('template-parts/messages/parts/inquiry-message/buyer-info', '', []); ?>

                </div>
            </div>
        </div>
    </div>
</div>
