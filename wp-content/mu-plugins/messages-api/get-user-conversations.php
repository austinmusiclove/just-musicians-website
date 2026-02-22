<?php

function get_user_conversations($request) {
    global $user_messages_plugin;

    // Get conversations
    $cursor        = $_GET['cursor'] ?? null;
    $get_newer     = isset($_GET['update']);
    $inquiry_id    = $_GET['inquiry_id'] ?? null;
    $user_id       = get_current_user_id();
    $limit         = 20;
    $conversations = $user_messages_plugin->get_user_conversations($user_id, $limit, $cursor, $inquiry_id, $get_newer);

    // Handle error
    if (is_wp_error($conversations)) {
        return $conversations;

    // Return formatted conversations
    } else {
        $formatted = array_map('formatConversation', $conversations);
        return array_values(array_filter($formatted));
    }
}

function formatConversation($conversation) {
    if (empty($conversation->participants)) { return null; } // Don't show conversations that don't have any other participants anymore; could happen if others leave convo or listings are deleted
    return [
        'conversation_id'           => $conversation->conversation_id,
        'title'                     => html_entity_decode(implode(', ', $conversation->participants)),
        'latest_message_content'    => html_entity_decode($conversation->content),
        'latest_message_created_at' => $conversation->created_at,
        'latest_message_sender_id'  => $conversation->sender_id,
        'latest_message_id'         => $conversation->message_id,
        'latest_message_is_read'    => $conversation->is_read ? true : false,
        'messages'                  => [],
    ];
}
