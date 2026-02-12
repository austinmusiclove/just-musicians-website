<?php

function get_account_notification_count() {
    if (!is_user_logged_in()) {
        return 0;
    }

    $user_id = get_current_user_id();
    $user = get_userdata($user_id);

    $count = 0;

    // Check display name
    if (empty($user->display_name) or clean_display_name($user->display_name) != $user->display_name) {
        $count++;
    }

    // Check avatar meta field (change 'avatar' if your meta key is different)
    $avatar = get_user_meta($user_id, 'avatar', true);
    if (empty($avatar)) {
        $count++;
    }

    return $count;
}

