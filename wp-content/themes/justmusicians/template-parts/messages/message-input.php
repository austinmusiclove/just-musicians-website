
<div class="border-t border-black/20 px-4 pt-2">
    <form class="flex items-end">
        <textarea class="p-2 w-full border border-black/20 rounded resize-none overflow-hidden focus:outline-none" name="content" rows="1" placeholder="Please enter a message."
            x-ref="messageInput"
            x-bind:disabled="conversationId < 0 || messageInFlight"
            x-on:input="$el.rows = $el.value.split(/\r\n|\r|\n/).length;"
            x-on:keydown="if ($event.key === 'Enter' && !$event.shiftKey) { $event.preventDefault(); _sendMessage(conversationId, $el.value); }"
        ></textarea>
        <button type="button" class="w-fit relative ml-2 bg-navy text-white hover:bg-yellow hover:text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3 disabled:opacity-50"
            x-bind:disabled="conversationId < 0 || messageInFlight"
            x-on:click="_sendMessage(conversationId, $refs.messageInput.value)"
        >
            <span x-show="!messageInFlight" x-cloak>Send</span>
            <span class="absolute inset-0 flex items-center justify-center" x-show="messageInFlight" x-cloak>
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>
    </form>
</div>
