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
    register_rest_route( 'v1', '/notifications_count/', [
        'methods' => 'GET',
        'callback' => 'get_notification_count',
        'permission_callback' => 'is_user_logged_in',
    ]);
});

function get_notification_count() {
    $unread_convo_count         = get_unread_conversation_count();
    $account_notification_count = get_account_notification_count();
    $db_notifications           = get_notifications_from_db();

    $db_counts      = $db_notifications['counts'];
    $db_subject_ids = $db_notifications['subject_ids'];

    $gig_notification_count   = isset($db_counts['new-inquiry'])      ? (int) $db_counts['new-inquiry']      : 0;
    $event_notification_count = isset($db_counts['inquiry-response']) ? (int) $db_counts['inquiry-response'] : 0;

    $new_inquiry_proposal_ids      = isset($db_subject_ids['new-inquiry'])      ? $db_subject_ids['new-inquiry']      : [];
    $inquiry_response_proposal_ids = isset($db_subject_ids['inquiry-response']) ? $db_subject_ids['inquiry-response'] : [];

    $total_notifications = array_sum([
        $account_notification_count,
        $unread_convo_count,
        $gig_notification_count,
        $event_notification_count,
    ]);

    return [
        'unread_convo_count'              => $unread_convo_count,
        'account_notification_count'      => $account_notification_count,
        'gig_notification_count'          => $gig_notification_count,
        'event_notification_count'        => $event_notification_count,
        'total_notification_count'        => $total_notifications,
        'new_inquiry_proposal_ids'        => $new_inquiry_proposal_ids,
        'inquiry_response_proposal_ids'   => $inquiry_response_proposal_ids,
    ];
}
