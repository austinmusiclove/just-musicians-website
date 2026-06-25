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
require_once 'notifications-api/get-notifications-from-db.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', '/user-notifications/', [
        'methods' => 'GET',
        'callback' => 'get_user_notifications',
        'permission_callback' => 'is_user_logged_in',
    ]);
});

function get_user_notifications() {
    $notifications = get_notifications_from_db();

    $notifications['unread_convo'] = [
        'count'       => (int) get_unread_conversation_count(),
        'subject_ids' => [],
    ];

    $notifications['account'] = [
        'count'       => (int) get_account_notification_count(),
        'subject_ids' => [],
    ];

    return $notifications;
}
