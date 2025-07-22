<?php
global $user_messages_plugin;

// Get args
$content = wp_kses_post(wp_unslash($_POST['content']));
$user_id = get_current_user_id();
$display_name = get_display_name($user_id);
$conversation_id = get_query_var('conversation-id') ?? 0;
if (!$conversation_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Failed to get messages for this conversation\'})"></span>'; exit;
}

// Send message
$result = $user_messages_plugin->send_message($conversation_id, $user_id, $content);

// Handle error
if (is_wp_error($result)) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $result->get_error_message() . '\'})"></span>'; exit;

// Handle success
} else if ($result) {
    echo get_template_part('template-parts/messages/basic-message', '', [
        'content'        => $content,
        'is_outgoing'    => true,
        'sender_name'     => $display_name,
        'conversation_id' => $conversation_id,
        'message_id'      => $result['id'],
        'timestamp'       => '', //$result['created_at'],
        'last'            => false,
    ]);
}
