<div class="text-14"
    :class="{ 'text-right': message.is_outgoing, 'text-left': !message.is_outgoing }"
    x-text="message.sender_name"
></div>
