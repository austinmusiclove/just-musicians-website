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
        $message = [
            'inquiry'                  => null,
            'content'                  => nl2br($message['content']),
            'is_outgoing'              => true,
            'sender_id'                => $message['sender_id'],
            'sender_name'              => $display_name,
            'sender_profile_image_url' => $message['sender_profile_image_url'],
            'message_id'               => (string) $message['message_id'],
            'conversation_id'          => (string) $message['conversation_id'],
            'created_at'               => $message['created_at'],
        ];

        // Create cron task to send notifications
        if (!wp_next_scheduled('send_notifications_after_message_send', [$message])) {
            wp_schedule_single_event(time() + NEW_MESSAGE_NOTIFICATION_DELAY, 'send_notifications_after_message_send', [$message]);
        }

        return $message;
    }
}

// check if message has been read by each user participant user and if not, notify them via email
add_action('send_notifications_after_message_send', function($message) {
    global $user_messages_plugin;
    $participants = $user_messages_plugin->get_conversation_participants_user_ids($message['conversation_id'], $message['sender_id'], true);
    foreach ($participants as $user_id) {
        $is_read = $user_messages_plugin->is_read($message['message_id'], $user_id);
        if (!$is_read) { send_new_message_notification($user_id); }
    }
});

function send_new_message_notification($user_id) {
    $user = get_userdata($user_id);
    $email = $user->user_email;
    $subject = 'You have a new message!';
    $message = 'You have a new message in your inbox. Visit ' . site_url('/messages') . ' to check your messages.';
    if (EMAIL_TEST_MODE) {
        wp_mail( ADMIN_NOTIFICATION_EMAIL, '(' . $email . ') ' . $subject, $message);
    } else {
        wp_mail($email, $subject, $message);
    }
}
