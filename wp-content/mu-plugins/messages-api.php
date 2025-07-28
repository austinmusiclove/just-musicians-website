<?php
/**
 * Plugin Name: Hire More Musicians Messages API
 * Description: A custom plugin to expose REST APIs for supporting chat applications
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/conversations/', [
        'methods' => 'GET',
        'callback' => 'get_user_conversations',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1', '/messages/(?P<conversation_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_conversation_messages',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1', '/messages/(?P<conversation_id>\d+)', [
        'methods' => 'POST',
        'callback' => 'send_message',
        'permission_callback' => 'is_user_logged_in',
    ]);
});


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
            'content'         => nl2br($message['content']),
            'is_outgoing'     => true,
            'sender_name'     => $display_name,
            'conversation_id' => $message['conversation_id'],
            'message_id'      => $message['message_id'],
            'created_at'      => $message['created_at'],
            'last'            => false,
        ];
    }
}

function get_user_conversations($request) {
    global $user_messages_plugin;

    // Get conversations
    $cursor        = $_GET['cursor'] ?? null;
    $get_newer     = isset($_GET['update']);
    $user_id       = get_current_user_id();
    $conversations = $user_messages_plugin->get_user_conversations($user_id, 20, $cursor, $get_newer);

    // Handle error
    if (is_wp_error($conversations)) {
        return $conversations;

    // Return formatted conversations
    } else {
        function formatConversation($conversation) {
            return [
                'conversation_id'           => $conversation->conversation_id,
                'title'                     => implode(', ', $conversation->participants),
                'latest_message_content'    => $conversation->content,
                'latest_message_created_at' => $conversation->created_at,
                'latest_message_sender_id'  => $conversation->sender_id,
                'latest_message_id'         => $conversation->message_id,
                'latest_message_is_read'    => $conversation->is_read,
                'messages'                  => [],
            ];
        }
        $formatted = array_map('formatConversation', $conversations);
        return $formatted;
    }
}


function get_conversation_messages($request) {
    global $user_messages_plugin;

    // Get messages
    $cursor          = $_GET['cursor'] ?? null;
    $get_newer       = isset($_GET['update']);
    $conversation_id = $request['conversation_id'];
    $messages        = $user_messages_plugin->get_conversation_messages($conversation_id, 20, $cursor, $get_newer);

    // Handle error
    if (is_wp_error($messages)) {
        return $messages;

    // Return messages
    } else {
        function formattedMessage($message) {
            $user_id = get_current_user_id();
            $display_name = clean_display_name($message->sender_name);
            $inquiry = null;
            if ($message->inquiry_id) {
                $inquiry = get_post_meta($message->inquiry_id);
            }
            return [
                'inquiry'         => $inquiry,
                'content'         => nl2br($message->content),
                'sender_name'     => $display_name,
                'is_outgoing'     => $message->sender_id == $user_id,
                'conversation_id' => $message->conversation_id,
                'message_id'      => $message->id,
                'created_at'      => $message->created_at,
            ];
        }
        $formatted = array_map('formattedMessage', $messages);
        return $formatted;
    }
}
