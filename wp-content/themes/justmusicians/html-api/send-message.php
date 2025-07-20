<?php
global $user_messages_plugin;

// Get args
$content = sanitize_text_field($_POST['content']);
$user_id = get_current_user_id();
$conversation_id = get_query_var('conversation-id') ?? 0;
if (!$conversation_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Failed to get messages for this conversation\'})"></span>'; exit;
}

$result = $user_messages_plugin->send_message($conversation_id, $user_id, $content);

if ($result) {
    echo get_template_part('template-parts/messages/basic-message', '', [
        'content'        => $content,
        'is_outgoing'    => true,
    ]);
} else {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Failed to send messages\'})"></span>'; exit;
}
