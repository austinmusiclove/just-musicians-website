<?php
/**
 * Plugin Name: Hire More Musicians Messages API
 * Description: A custom plugin to expose REST APIs for supporting chat applications
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'messages-api/get-user-conversations.php';
require_once 'messages-api/get-conversation-messages.php';
require_once 'messages-api/send-message.php';
require_once 'messages-api/add-read-receipt.php';
require_once 'messages-api/remove-read-receipt.php';

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
    register_rest_route( 'v1', '/read_receipts/(?P<message_id>\d+)/(?P<user_id>\d+)', [
        'methods' => 'POST',
        'callback' => 'add_read_receipt',
        'permission_callback' => 'is_user_logged_in',
    ]);
    register_rest_route( 'v1', '/read_receipts/(?P<message_id>\d+)/(?P<user_id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'remove_read_receipt',
        'permission_callback' => 'is_user_logged_in',
    ]);
});
