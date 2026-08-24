<?php

define('HM_VIEW_TYPE_MGMT', 'view');
define('HM_EDIT_TYPE_MGMT', 'edit');
define('HM_ACCESS_TYPE_OWNER', 'owner');

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

function hm_get_subject_ids_for_user($user_id, $access_type, $subject_type = null) {
    global $wpdb;
    $table = hm_get_access_table();

    $sql = "SELECT subject_id FROM {$table} WHERE user_id = %d AND access_type = %s";
    $params = [$user_id, $access_type];

    if ($subject_type !== null) {
        $sql .= " AND subject_type = %s";
        $params[] = $subject_type;
    }

    $rows = $wpdb->get_col($wpdb->prepare($sql, $params));

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

function hm_get_access_by_id($access_id) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT id, user_id, subject_id, access_type FROM {$table} WHERE id = %d",
        $access_id
    ));
}

function hm_get_access_entries($subject_id, $access_type = null) {
    global $wpdb;
    $table = hm_get_access_table();

    $sql = "SELECT a.id, a.user_id, a.access_type, u.display_name, u.user_email
            FROM {$table} a
            LEFT JOIN {$wpdb->users} u ON u.ID = a.user_id
            WHERE a.subject_id = %s";
    $params = [(string) $subject_id];

    if ($access_type !== null) {
        $sql .= " AND a.access_type = %s";
        $params[] = $access_type;
    }

    $rows = $wpdb->get_results($wpdb->prepare($sql . " ORDER BY a.created_at DESC", $params));

    return $rows ?: [];
}
