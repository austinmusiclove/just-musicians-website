<?php

if (!is_user_logged_in()) {
    exit;
}

$notification_type = isset($_POST['notification_type']) ? sanitize_text_field($_POST['notification_type']) : '';
$subject_id        = isset($_POST['subject_id'])        ? (int) $_POST['subject_id']                     : 0;

if (empty($notification_type) || empty($subject_id)) {
    exit;
}

$user_id = get_current_user_id();

clear_notification($user_id, $notification_type, $subject_id);

$notifications = get_notification_count();
$notifications_json = clean_arr_for_doublequotes($notifications);
echo '<span x-init="notifications = ' . $notifications_json . '"></span>';
