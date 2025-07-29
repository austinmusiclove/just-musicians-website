<?php

function send_message(WP_REST_Request $request) {
    global $user_messages_plugin;
    $user_id = get_current_user_id();
    $display_name = get_display_name($user_id);
    $conversation_id = $request['conversation_id'];
    $content = wp_kses_post(wp_unslash($request->get_json_params()['content']));

    // Send message
    $message = $user_messages_plugin->send_message($conversation_id, $user_id, $content);

    // Handle error
    if (is_wp_error($message)) {
        return $message;

    // Handle success
    } else if ($message) {
        return [
            'inquiry'         => null,
            'content'         => nl2br($message['content']),
            'is_outgoing'     => true,
            'sender_name'     => $display_name,
            'conversation_id' => $message['conversation_id'],
            'message_id'      => (string) $message['message_id'],
            'created_at'      => $message['created_at'],
        ];
    }
}

