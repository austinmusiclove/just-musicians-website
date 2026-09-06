<?php

add_action('wp_trash_post', 'hm_access_on_delete_post', 20);
add_action('delete_post', 'hm_access_on_delete_post', 20);
add_action('transition_post_status', 'hm_access_on_new_post', 20, 3);

function hm_access_post_types() {
    return ['application', 'collection', 'listing', 'event', 'venue'];
}

function hm_upsert_access($email, $subject_id, $subject_type, $access_type) {
    global $wpdb;
    $table = hm_get_access_table();

    if (!$email || $subject_id === '' || $subject_id === null || empty($access_type) || empty($subject_type)) {
        return 'incomplete';
    }

    $user    = get_user_by('email', $email);
    $user_id = $user ? $user->ID : null;
    $user_sql = $user_id ? (int) $user_id : 'NULL';

    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO {$table} (user_id, email, subject_id, subject_type, access_type)
         VALUES ({$user_sql}, %s, %s, %s, %s)
         ON DUPLICATE KEY UPDATE access_type = VALUES(access_type), email = VALUES(email), created_at = CURRENT_TIMESTAMP",
        $email,
        (string) $subject_id,
        (string) $subject_type,
        $access_type
    ));

    return $result === false ? 'error' : 'inserted';
}

function hm_grant_access_by_email($email, $subject_id, $subject_type, $access_type) {
    if (!$email) {
        return ['type' => null, 'user' => null, 'is_new' => false, 'result' => false];
    }

    $user   = get_user_by('email', $email);
    $is_new = $user
        ? !hm_get_user_access_type($user->ID, $subject_id, $subject_type)
        : !hm_get_access_invite_type($email, $subject_id, $subject_type);
    $result = hm_upsert_access($email, $subject_id, $subject_type, $access_type) !== 'error';

    return [
        'type'   => $user ? 'user' : 'invite',
        'user'   => $user,
        'is_new' => $is_new,
        'result' => $result,
    ];
}

function hm_revoke_access($user_id, $subject_id, $subject_type) {
    global $wpdb;
    $table = hm_get_access_table();

    return $wpdb->delete($table, [
        'user_id'    => (int) $user_id,
        'subject_id' => (string) $subject_id,
    ], ['%d', '%s']) !== false;
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

    $author_email = get_the_author_meta('user_email', $author_id);
    if (!$author_email) {
        return 0;
    }

    $inserted = 0;
    $failed   = false;

    if (hm_upsert_access($author_email, $post->ID, $post->post_type, HM_ACCESS_TYPE_OWNER) === 'inserted') {
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

function hm_apply_access_invites_on_register($user_id) {
    $user = get_userdata($user_id);
    if (!$user) {
        return 0;
    }

    global $wpdb;
    $table   = hm_get_access_table();
    $invites = hm_get_access_invites_by_email($user->user_email);
    if (empty($invites)) {
        return 0;
    }

    $applied = 0;

    foreach ($invites as $invite) {
        if ($wpdb->update($table, ['user_id' => $user_id], ['id' => (int) $invite->id], ['%d'], ['%d']) !== false) {
            $applied++;
        }
    }

    return $applied;
}
add_action('user_register', 'hm_apply_access_invites_on_register', 10, 1);
