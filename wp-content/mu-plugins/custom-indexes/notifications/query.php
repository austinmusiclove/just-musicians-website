<?php
if (!defined('ABSPATH')) { exit; }

function add_new_inquiry_notification($user_id, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => 'new-inquiry',
        'subject_id'        => (int) $subject_id,
    ]);
}

function add_inquiry_response_notification($user_id, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => 'inquiry-response',
        'subject_id'        => (int) $subject_id,
    ]);
}
