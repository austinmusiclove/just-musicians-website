
<div id="message-board" class="flex-1 overflow-y-auto p-6 space-y-4" x-ref="messageBoard">

    <template x-if="conversationId > 0">


        <template x-for="(message, index) in conversationsMap[conversationId].messages" :key="index">
            <span>


                <!-- Display Basic Message -->
                <template x-if="index > 0 && !message.inquiry">
                    <?php echo get_template_part('template-parts/messages/basic-message', '', ['is_last' => false]); ?>
                </template>
                <!-- Display Inquiry Message -->
                <template x-if="index > 0 && message.inquiry">
                    <?php echo get_template_part('template-parts/messages/inquiry-message', '', ['is_last' => false]); ?>
                </template>

                <!-- Display last message: distinct from other conversations for pagination purposes -->
                <!-- Display Basic Message (last) -->
                <template x-if="index == 0 && !message.inquiry">
                    <?php echo get_template_part('template-parts/messages/basic-message', '', ['is_last' => true]); ?>
                </template>
                <!-- Display Inquiry Message (last) -->
                <template x-if="index == 0 && message.inquiry">
                    <?php echo get_template_part('template-parts/messages/inquiry-message', '', ['is_last' => true]); ?>
                </template>


            </span>
        </template>


    </template>

</div>
