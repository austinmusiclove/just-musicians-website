<?php
/**
 * The template for the messages landing page
 *
 * @package JustMusicians
 */

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page
//$conversations = $user_messages_plugin->get_user_conversations(get_current_user_id());
/*
        conversations: <?php if (!empty($conversations)) { echo clean_arr_for_doublequotes($conversations); } else { echo '[]'; } ?>,
 */
get_header();


?>

<div id="sticky-sidebar" class="hidden lg:block fixed top-0 z-10 left-0 bg-white h-screen dropshadow-md px-3 w-fit pt-40 border-r border-black/20">
    <div class="sidebar">
        <?php echo get_template_part('template-parts/account/sidebar', '', [
            'collapsible' => true
        ]); ?>
    </div>
</div>

<div class="lg:container h-[69vh]"
    x-data="{
        conversation_id: -1,
        conversation_title: '',
        conversations: [],
        _scrollToElement(id) { document.getElementById(id).scrollIntoView(); },
        _afterMessageSend() {
            $refs.content.value = '';
            $refs.content.rows = 1;
        },
        _selectConversation(conversation_id, conversation_title) {
            console.log(conversation_id);
            this.conversation_id    = conversation_id;
            this.conversation_title = conversation_title;
            $nextTick(() => {
                htmx.process($refs.getMessages);
                htmx.process($refs.sendMessage);
                this.$dispatch('fetchmessages');
            } );
        },
    }"
    x-on:message-sent.window="_afterMessageSend()"
    x-on:scroll-to.window="_scrollToElement($event.detail.id)"
    hx-get="<?php echo site_url('/wp-html/v1/conversations/'); ?>"
    hx-trigger="load"
    hx-target="#conversation-container"
    hx-indicator="#cv-spinner"
>
    <div class="px-4 lg:pr-0 md:pl-12 lg:pl-0 lg:grid lg:grid-cols-12 gap-12 xl:gap-28">

        <!-- Conversations Menu -->
        <div class="flex flex-col col-span-12 lg:col-span-3 z-0 border-r border-black/20 h-[69vh]">
            <header class="pt-4 sm:pt-20 xl:pt-32 mb-4 sm:mb-12 gap-12 sm:gap-4 flex flex-col-reverse sm:flex-row justify-between sm:items-center">
                <h1 class="font-bold text-22 sm:text-25">Conversations</h1>
            </header>

            <!-- Get Conversations -->
            <div id="conversation-container" class="flex-1 border-t border-black/20 w-full overflow-y-scroll"
                x-ref="getMessages"
                x-bind:hx-get="'<?php echo site_url(); ?>' + '/wp-html/v1/messages/' + conversation_id + '/'"
                hx-trigger="fetchmessages"
                hx-target="#message-board"
                hx-indicator="#mb-spinner"
                hx-swap="scroll:bottom"
            >
                <template x-for="conversation in conversations" :key="conversation.conversation_id">
                    <div class="px-3 py-4 border-b border-black/20 hover:bg-yellow-10" x-on:click="_selectConversation(conversation.conversation_id, conversation.participants.join(', '));">
                        <h3 class="text-18 font-bold mb-2" x-text="conversation.participants.join(', ')"></h3>
                        <p class="w-full text-sm truncate" x-text="conversation.content"></p>
                    </div>
                </template>
            </div>

            <!-- Spinner -->
            <div id="cv-spinner" class="flex items-center justify-center htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
            </div>

        </div>

        <!-- Messageing App -->
        <div class="hidden pb-4 lg:flex flex-col lg:col-span-9 h-[69vh]">

            <!-- Message Bubbles -->
            <div>
                <h2 class="my-8 font-bold text-22" x-text="conversation_title"></h2>
            </div>
            <div id="mb-spinner" class="flex items-center justify-center htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
            </div>
            <div id="message-board" class="flex-1 overflow-y-auto p-6 space-y-4" x-ref="messageBoard"></div>

            <!-- Message Input Area -->
            <div class="border-t border-black/20 px-4 pt-2">
                <form class="flex items-end"
                    x-ref="sendMessage"
                    x-bind:hx-post="'<?php echo site_url(); ?>' + '/wp-html/v1/messages/' + conversation_id"
                    hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
                    hx-target="#message-board"
                    hx-swap="beforeend scroll:bottom"
                    hx-ext="disable-element" hx-disable-element=".htmx-submit-button"
                    hx-indicator=".htmx-submit-button"
                    hx-on::after-request="if (event.detail.successful) { dispatchEvent(new CustomEvent('message-sent')); }"
                >
                    <textarea class="p-2 w-full border border-black/20 rounded resize-none overflow-hidden focus:outline-none" name="content" rows="1" placeholder="Please enter a message."
                        x-bind:disabled="conversation_id < 0"
                        x-ref="content"
                        x-on:input="$el.rows = $el.value.split(/\r\n|\r|\n/).length;"
                        x-on:keydown="if ($event.key === 'Enter' && !$event.shiftKey) { $event.preventDefault(); $refs.sendMessage.requestSubmit(); }"
                    ></textarea>
                    <button type="submit" class="htmx-submit-button w-fit relative ml-2 bg-navy text-white hover:bg-yellow hover:text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3 disabled:opacity-50"
                        x-bind:disabled="conversation_id < 0"
                    >
                        <span class="htmx-indicator-replace">Send</span>
                        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                        </span>
                    </button>
                </form>
            </div>

        </div>


    </div>
</div>

<?php
get_footer();


