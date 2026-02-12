<?php
/**
 * Plugin Name: Hire More Musicians Notifications API
 * Description: A custom plugin to expose REST APIs for supporting user notifications
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'messages-api/get-unread-conversation-count.php';
require_once 'notifications-api/get-account-notifications.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/notifications_count/', [
        'methods' => 'GET',
        'callback' => 'get_notification_count',
        'permission_callback' => 'is_user_logged_in',
    ]);
});

function get_notification_count($request) {
    $unread_convo_count         = get_unread_conversation_count();
    $account_notification_count = get_account_notification_count();
    $total_notifications        = $account_notification_count + $unread_convo_count;
    return [
        'unread_convo_count'         => $unread_convo_count,
        'account_notification_count' => $account_notification_count,
        'total_notification_count'   => $total_notifications,
    ];
}
