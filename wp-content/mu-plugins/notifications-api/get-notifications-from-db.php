<?php
if (!defined('ABSPATH')) { exit; }

function get_notifications_from_db() {
    if (!is_user_logged_in()) {
        return [];
    }

    global $wpdb;
    $table   = hm_get_notifications_table();
    $user_id = get_current_user_id();

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT notification_type, subject_id FROM {$table} WHERE user_id = %d",
        $user_id
    ));

    $notifications = [];

    foreach ($rows as $row) {
        $type = $row->notification_type;

        if (!isset($notifications[$type])) {
            $notifications[$type] = [
                'count'       => 0,
                'subject_ids' => [],
            ];
        }

        $notifications[$type]['count']++;
        $notifications[$type]['subject_ids'][] = $row->subject_id;
    }

    return $notifications;
}
