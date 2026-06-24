<?php

$user_id = get_current_user_id();
if (!$user_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'You must be logged in to send a message.\'})"></span>';
    exit;
}

$message_content = isset($_POST['message'])     ? sanitize_textarea_field($_POST['message']) : '';
$listing_id      = isset($_POST['listing_id'])  ? sanitize_text_field($_POST['listing_id']) : '';

if (!$listing_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Invalid listing ID.\'})"></span>';
    exit;
}

if (empty($message_content)) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Message cannot be empty.\'})"></span>';
    exit;
}

global $user_messages_plugin;
if (!isset($user_messages_plugin)) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Messaging system not available.\'})"></span>';
    exit;
}

$conversation_id = $user_messages_plugin->create_conversation([$user_id], [$listing_id]);

$result = $user_messages_plugin->send_message($conversation_id, $user_id, $message_content, null, null, null);

if (is_wp_error($result)) {
    $error_msg = 'Error sending message: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $error_msg . '\'})"></span>';
    exit;
}

echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Message sent successfully!\'}); showSendMessageModal = false;"></span>';
