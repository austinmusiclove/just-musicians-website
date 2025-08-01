<?php

function get_conversation_messages($request) {
    global $user_messages_plugin;

    // Get messages
    $cursor          = $_GET['cursor'] ?? null;
    $get_newer       = isset($_GET['update']);
    $conversation_id = $request['conversation_id'];
    $messages = $user_messages_plugin->get_conversation_messages($conversation_id, 20, $cursor, $get_newer);
    if (is_wp_error($messages)) { return $messages; }

    $user_id = get_current_user_id();
    return array_map(function($message) use ($user_id) {
        return formatMessage($message, $user_id);
    }, $messages);
}

function formatMessage($message, $user_id) {
    $display_name = clean_display_name($message->sender_name);
    $inquiry = null;
    if ($message->inquiry_id) {
        $inquiry = get_post_meta($message->inquiry_id);
        if (empty($inquiry)) { $inquiry = ['subject' => 'Inquiry Expired']; }
    }
    return [
        'message_id'      => $message->id,
        'conversation_id' => $message->conversation_id,
        'sender_id'       => $message->sender_id,
        'created_at'      => $message->created_at,
        'updated_at'      => $message->updated_at,
        'is_read'         => $message->is_read ? true : false,
        'content'         => nl2br($message->content),
        'inquiry'         => $inquiry,
        'sender_name'     => $display_name,
        'is_outgoing'     => $message->sender_id == $user_id,
    ];
}
