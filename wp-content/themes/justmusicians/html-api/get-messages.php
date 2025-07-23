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

// Handle error
if (is_wp_error($messages)) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $messages->get_error_message() . '\'})"></span>'; exit;

// Display messages
} else {
    $num_messages = count($messages);
    if ($num_messages > 0) {

        // Traverse messages in reverse to display in chronological order
        for ($iter = $num_messages - 1; $iter >= 0; $iter--) {
            $message = $messages[$iter];
            $display_name = clean_display_name($message->sender_name);

            // Inquiry message
            if ($message->inquiry_id) {
                $inquiry = get_post_meta($message->inquiry_id);
                echo get_template_part('template-parts/messages/inquiry-message', '', [
                    'inquiry'         => $inquiry,
                    'sender_name'     => $display_name,
                    'is_outgoing'     => $message->sender_id == $user_id,
                    'conversation_id' => $message->conversation_id,
                    'message_id'      => $message->id,
                    'timestamp'       => $message->created_at,
                    'last'            => $iter === $num_messages - 1,
                ]);


            // Regular message
            } else {
                echo get_template_part('template-parts/messages/basic-message', '', [
                    'content'         => $message->content,
                    'sender_name'     => $display_name,
                    'is_outgoing'     => $message->sender_id == $user_id,
                    'conversation_id' => $message->conversation_id,
                    'message_id'      => $message->id,
                    'timestamp'       => $message->created_at,
                    'last'            => $iter === $num_messages - 1,
                ]);
            }


        }
    }
}
