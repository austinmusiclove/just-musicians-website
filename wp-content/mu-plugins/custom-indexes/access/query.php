<?php

define('HM_ACCESS_TYPE_VIEW', 'view');
define('HM_ACCESS_TYPE_EDIT', 'edit');
define('HM_ACCESS_TYPE_OWNER', 'owner');

function hm_user_has_access($user_id, $subject_id, $access_type, $subject_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $found = $wpdb->get_var($wpdb->prepare(
        "SELECT 1 FROM {$table} WHERE user_id = %d AND subject_id = %s AND access_type = %s AND subject_type = %s LIMIT 1",
        $user_id,
        (string) $subject_id,
        $access_type,
        $subject_type
    ));

    return (bool) $found;
}

function hm_get_user_access_type($user_id, $subject_id, $subject_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $access_type = $wpdb->get_var($wpdb->prepare(
        "SELECT access_type FROM {$table} WHERE user_id = %d AND subject_id = %s AND subject_type = %s LIMIT 1",
        $user_id,
        (string) $subject_id,
        (string) $subject_type
    ));

    return $access_type ?: null;
}

function hm_get_subject_ids_for_user($user_id, $access_type, $subject_type = null) {
    global $wpdb;
    $table = hm_get_access_table();

    $sql = "SELECT subject_id FROM {$table} WHERE user_id = %d";
    $params = [$user_id];

    if (is_array($access_type)) {
        $placeholders = implode(', ', array_fill(0, count($access_type), '%s'));
        $sql .= " AND access_type IN ({$placeholders})";
        $params = array_merge($params, $access_type);
    } else {
        $sql .= " AND access_type = %s";
        $params[] = $access_type;
    }

    if ($subject_type !== null) {
        $sql .= " AND subject_type = %s";
        $params[] = $subject_type;
    }

    $rows = $wpdb->get_col($wpdb->prepare($sql, $params));

    return $rows ?: [];
}

function hm_get_access_by_id($access_id) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->get_row($wpdb->prepare(
        "SELECT id, user_id, email, subject_id, subject_type, access_type FROM {$table} WHERE id = %d",
        $access_id
    ));
}

function hm_get_access_entries($subject_id, $access_type = null) {
    global $wpdb;
    $table = hm_get_access_table();

    $sql = "SELECT a.id, a.user_id, a.email, a.access_type, a.subject_type, a.subject_id, a.created_at, u.display_name,
                   COALESCE(u.user_email, a.email) AS user_email
            FROM {$table} a
            LEFT JOIN {$wpdb->users} u ON u.ID = a.user_id
            WHERE a.subject_id = %s";
    $params = [(string) $subject_id];

    if ($access_type !== null) {
        $sql .= " AND a.access_type = %s";
        $params[] = $access_type;
    }

    $rows = $wpdb->get_results($wpdb->prepare($sql . " ORDER BY a.created_at DESC", $params));
    if ($rows) {
        foreach ($rows as $row) {
            $row->source = empty($row->user_id) ? 'invite' : 'user';
        }
    } else {
        $rows = [];
    }

    return $rows;
}

function hm_get_access_invite_type($email, $subject_id, $subject_type) {
    global $wpdb;
    $table = hm_get_access_table();

    $access_type = $wpdb->get_var($wpdb->prepare(
        "SELECT access_type FROM {$table} WHERE email = %s AND user_id IS NULL AND subject_type = %s AND subject_id = %s LIMIT 1",
        $email,
        (string) $subject_type,
        (string) $subject_id
    ));

    return $access_type ?: null;
}

function hm_get_access_invites_by_email($email) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table} WHERE email = %s AND user_id IS NULL",
        $email
    ));
}
