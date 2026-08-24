<?php

add_action('delete_post', 'hm_access_on_delete_post', 20);
add_action('transition_post_status', 'hm_access_on_new_post', 20, 3);

function hm_access_post_types() {
    return ['application', 'collection', 'listing', 'event', 'venue'];
}

function hm_upsert_access($user_id, $subject_id, $access_type, $subject_type) {
    global $wpdb;
    $table = hm_get_access_table();

    if (empty($user_id) || $subject_id === '' || $subject_id === null || empty($access_type) || empty($subject_type)) {
        return 'incomplete';
    }

    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO {$table} (user_id, subject_id, subject_type, access_type)
         VALUES (%d, %s, %s, %s)
         ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP",
        $user_id,
        (string) $subject_id,
        (string) $subject_type,
        $access_type
    ));

    return $result === false ? 'error' : 'inserted';
}

function hm_grant_access($user_id, $subject_id, $access_type, $subject_type) {
    return hm_upsert_access($user_id, $subject_id, $access_type, $subject_type) !== 'error';
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

function hm_revoke_access_by_id($access_id) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->delete($table, ['id' => (int) $access_id], ['%d']) !== false;
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

function hm_access_grant_author_access($post_id) {
    $post = get_post($post_id);

    if (!$post || !in_array($post->post_type, hm_access_post_types(), true)) {
        return 0;
    }

    $author_id = (int) $post->post_author;
    if (!$author_id) {
        return 0;
    }

    $inserted = 0;
    $failed   = false;

    if (hm_upsert_access($author_id, $post->ID, HM_ACCESS_TYPE_OWNER, $post->post_type) === 'inserted') {
        $inserted++;
    } else {
        $failed = true;
    }

    return $failed ? new WP_Error('insert_failed', 'Failed to grant author access') : $inserted;
}

function hm_access_on_new_post($new_status, $old_status, $post) {
    if (!in_array($old_status, ['new', 'auto-draft'], true)) { return; }
    if (in_array($new_status, ['auto-draft', 'trash', 'inherit'], true)) { return; }
    if (wp_is_post_revision($post->ID) || wp_is_post_autosave($post->ID)) { return; }

    hm_access_grant_author_access($post->ID);
}
