<?php
if (!defined('ABSPATH')) { exit; }

function notification_exists($user_id, $notification_type, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $found = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND notification_type = %s AND subject_id = %d",
        (int) $user_id,
        $notification_type,
        (int) $subject_id
    ));

    return (int) $found > 0;
}

function add_new_inquiry_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, 'new-inquiry', $proposal_id)) {
        return;
    }

    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => 'new-inquiry',
        'subject_id'        => (int) $proposal_id,
    ]);
}

function add_inquiry_response_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, 'inquiry-response', $proposal_id)) {
        return;
    }

    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => 'inquiry-response',
        'subject_id'        => (int) $proposal_id,
    ]);
}

function add_inquiry_response_update_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, 'inquiry-response-update', $proposal_id)) {
        return;
    }
    if (notification_exists($user_id, 'inquiry-response', $proposal_id)) {
        return;
    }

    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => 'inquiry-response-update',
        'subject_id'        => (int) $proposal_id,
    ]);
}

function clear_notification($user_id, $notification_type, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->delete($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => $notification_type,
        'subject_id'        => (int) $subject_id,
    ]);
}
