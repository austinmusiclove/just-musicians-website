<?php

add_action('delete_post', 'hm_access_on_delete_post', 20);

function hm_upsert_access($user_id, $subject_id, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    if (empty($user_id) || $subject_id === '' || $subject_id === null || empty($access_type)) {
        return 'incomplete';
    }

    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO {$table} (user_id, subject_id, access_type)
         VALUES (%d, %s, %s)
         ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP",
        $user_id,
        (string) $subject_id,
        $access_type
    ));

    return $result === false ? 'error' : 'inserted';
}

function hm_grant_access($user_id, $subject_id, $access_type) {
    return hm_upsert_access($user_id, $subject_id, $access_type) !== 'error';
}

function hm_revoke_access($user_id, $subject_id, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->delete($table, [
        'user_id'     => (int) $user_id,
        'subject_id'  => (string) $subject_id,
        'access_type' => (string) $access_type,
    ], ['%d', '%s', '%s']) !== false;
}

function hm_revoke_all_access_for_subject($subject_id) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->delete($table, ['subject_id' => (string) $subject_id], ['%s']) !== false;
}

function hm_access_on_delete_post($post_id) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) { return; }

    hm_revoke_all_access_for_subject($post_id);
}
