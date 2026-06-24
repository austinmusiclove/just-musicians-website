<div class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4 sm:p-8" x-show="showSendMessageModal" x-cloak>

    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="showSendMessageModal = false; showSendMessageSuccess = false;"
    ></div>

    <div class="bg-white relative w-full h-full md:w-auto md:h-auto flex items-center justify-center p-4 sm:p-8" style="max-width: 780px;">

        <!-- X button -->
        <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer"
            src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="showSendMessageModal = false; showSendMessageSuccess = false;"
        />

        <!-- Message form -->
        <form id="send-message-form" class="p-8 w-full" style="width: 500px;" x-show="!showSendMessageSuccess" x-cloak>

            <h2 class="text-22 font-sun-motter mb-2">Send a message to <span x-text="sendMessageListingName"></span></h2>

            <textarea class="w-full border-2 border-black/20 rounded-sm p-3 text-14 mt-4 min-h-[200px] focus:border-yellow outline-none" x-model="sendMessageText" name="message" placeholder="Write your message..."></textarea>
            <input type="hidden" name="listing_id" x-model="sendMessageListingId" />

            <div class="flex justify-end gap-2 mt-4">
                <button type="button"
                    class="border-2 border-black px-4 py-2 text-14 font-sun-motter hover:bg-black/5"
                    x-on:click="showSendMessageModal = false; showSendMessageSuccess = false;"
                >Cancel</button>
                <button type="button"
                    class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-14 px-5 py-2 hover:bg-navy hover:text-white"
                    hx-post="<?php echo site_url('/wp-html/v1/send-message-listing/'); ?>"
                    hx-target="#send-message-result"
                    hx-indicator="#send-message-button-content"
                    hx-include="#send-message-form"
                >
                    <span id="send-message-button-content" class="flex justify-center">
                        <span class="htmx-indicator-component-block-replace">Send</span>
                        <span class="htmx-indicator-component-block">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                        </span>
                    </span>
                </button>
            </div>

            <span id="send-message-result"></span>

        </form>

        <!-- Success message -->
        <div class="slide w-[24rem] text-center" x-show="showSendMessageSuccess" x-cloak>
            <h2 class="font-bold font-poppins text-20 mb-4">Your message has been sent!</h2>
            <p class="text-18">You can view your <br><a class="underline text-yellow" href="<?php echo site_url('/messages/'); ?>">conversation here.</a></p>
        </div>

    </div>

</div>
