<?php

function hm_user_has_access($user_id, $subject_id, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $found = $wpdb->get_var($wpdb->prepare(
        "SELECT 1 FROM {$table} WHERE user_id = %d AND subject_id = %s AND access_type = %s LIMIT 1",
        $user_id,
        (string) $subject_id,
        $access_type
    ));

    return (bool) $found;
}

function hm_get_subject_ids_for_user($user_id, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT subject_id FROM {$table} WHERE user_id = %d AND access_type = %s",
        $user_id,
        $access_type
    ));

    return $rows ?: [];
}

function hm_get_users_with_access($subject_id, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT user_id FROM {$table} WHERE subject_id = %s AND access_type = %s",
        (string) $subject_id,
        $access_type
    ));

    return array_map('intval', $rows ?: []);
}
