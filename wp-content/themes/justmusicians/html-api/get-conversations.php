<?php
global $user_messages_plugin;

// Get conversations
$cursor = $_GET['cursor'] ?? null;
$user_id = get_current_user_id();
$conversations = $user_messages_plugin->get_user_conversations($user_id, 12, $cursor);

// Handle error
if (is_wp_error($conversations)) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $messages->get_error_message() . '\'})"></span>'; exit;

// Display conversations
} else {
    $num_conversations = count($conversations);
    if ($num_conversations > 0) {

        foreach($conversations as $index => $conversation) {
            echo get_template_part('template-parts/messages/conversation', '', [
                'conversation_id' => $conversation->conversation_id,
                'message_content' => $conversation->content,
                'title'           => implode(', ', $conversation->participants),
                'is_unread'       => false,
                'is_last'         => $index == $num_conversations - 1,
            ]);

        }
    }
}
