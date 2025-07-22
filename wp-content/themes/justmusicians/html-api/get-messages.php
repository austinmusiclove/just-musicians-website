<?php
global $user_messages_plugin;

// Get Messages
$cursor = $_GET['cursor'] ?? null;
$user_id = get_current_user_id();
$conversation_id = get_query_var('conversation-id') ?? 0;
if (!$conversation_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Failed to get messages for this conversation\'})"></span>'; exit;
}
$messages = $user_messages_plugin->get_conversation_messages($conversation_id, 20, $cursor);


// Display messages
$num_messages = count($messages);
if ($num_messages > 0) {

    // Traverse messages in reverse to display in chronological order
    for ($iter = $num_messages - 1; $iter >= 0; $iter--) {
        $message = $messages[$iter];
        echo get_template_part('template-parts/messages/basic-message', '', [
            'content'         => $message->content,
            'is_outgoing'     => $message->sender_id == $user_id,
            'conversation_id' => $message->conversation_id,
            'message_id'      => $message->id,
            'last'            => $iter === $num_messages - 1,
        ]);
    }

}
