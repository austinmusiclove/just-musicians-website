<?php

if (!is_user_logged_in()) {
    exit;
}

$notification_types = isset($_POST['notification_type']) ? sanitize_text_field($_POST['notification_type']) : '';
$subject_id         = isset($_POST['subject_id'])        ? (int) sanitize_text_field($_POST['subject_id'])  : 0;

if (empty($notification_types) || empty($subject_id)) {
    exit;
}

$user_id = get_current_user_id();
$types   = explode(',', $notification_types);

foreach ($types as $type) {
    if (!empty(trim($type))) {
        clear_notification($user_id, $type, $subject_id);
    }
}

echo '<span x-init="notifications = await get_user_notifications();"></span>';
