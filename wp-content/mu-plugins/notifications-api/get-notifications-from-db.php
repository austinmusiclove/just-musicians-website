<?php
if (!defined('ABSPATH')) { exit; }

function get_notifications_from_db() {
    if (!is_user_logged_in()) {
        return [
            'counts'      => [],
            'subject_ids' => [],
        ];
    }

    global $wpdb;
    $table   = hm_get_notifications_table();
    $user_id = get_current_user_id();

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT notification_type, subject_id FROM {$table} WHERE user_id = %d",
        $user_id
    ));

    $counts      = [];
    $subject_ids = [];

    foreach ($rows as $row) {
        $type = $row->notification_type;
        $sid  = $row->subject_id;

        if (!isset($counts[$type])) {
            $counts[$type]      = 0;
            $subject_ids[$type] = [];
        }

        $counts[$type]++;
        $subject_ids[$type][] = $sid;
    }

    return [
        'counts'      => $counts,
        'subject_ids' => $subject_ids,
    ];
}
