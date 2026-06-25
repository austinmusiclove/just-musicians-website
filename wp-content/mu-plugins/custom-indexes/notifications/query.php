<?php
if (!defined('ABSPATH')) { exit; }

define('HM_NOTIFICATION_TYPE_NEW_INQUIRY',             'new_inquiry');
define('HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE',        'inquiry_response');
define('HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE_UPDATE', 'inquiry_response_update');
define('HM_NOTIFICATION_TYPE_EVENT_DT_CHANGE',         'event_dt_change');

function notification_exists($user_id, $notification_type, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $found = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND notification_type = %s AND subject_id = %d",
        (int) $user_id,
        $notification_type,
        (int) $subject_id
    ));

    return (int) $found > 0;
}

function add_notification($user_id, $notification_type, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->insert($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => $notification_type,
        'subject_id'        => (int) $subject_id,
    ]);
}

function add_new_inquiry_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, HM_NOTIFICATION_TYPE_NEW_INQUIRY, $proposal_id)) return;
    add_notification($user_id, HM_NOTIFICATION_TYPE_NEW_INQUIRY, $proposal_id);
}

function add_inquiry_response_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE, $proposal_id)) return;
    add_notification($user_id, HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE, $proposal_id);
}

function add_inquiry_response_update_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE_UPDATE, $proposal_id)) return;
    if (notification_exists($user_id, HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE, $proposal_id)) return;
    add_notification($user_id, HM_NOTIFICATION_TYPE_INQUIRY_RESPONSE_UPDATE, $proposal_id);
}

function add_event_dt_change_notification($user_id, $proposal_id) {
    if (notification_exists($user_id, HM_NOTIFICATION_TYPE_EVENT_DT_CHANGE, $proposal_id)) return;
    add_notification($user_id, HM_NOTIFICATION_TYPE_EVENT_DT_CHANGE, $proposal_id);
}

function clear_notification($user_id, $notification_type, $subject_id) {
    global $wpdb;
    $table = hm_get_notifications_table();

    $wpdb->delete($table, [
        'user_id'           => (int) $user_id,
        'notification_type' => $notification_type,
        'subject_id'        => (int) $subject_id,
    ]);
}
