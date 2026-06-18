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
        "SELECT notification_type, COUNT(*) as count FROM {$table} WHERE user_id = %d GROUP BY notification_type",
        $user_id
    ));

    $notifications = [];
    foreach ($rows as $row) {
        $notifications[$row->notification_type] = (int) $row->count;
    }

    return $notifications;
}
