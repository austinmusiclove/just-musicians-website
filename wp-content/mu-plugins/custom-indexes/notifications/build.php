<?php
if (!defined('ABSPATH')) { exit; }

function hm_create_notifications_table() {
    global $wpdb;
    $table = hm_get_notifications_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id                BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id           BIGINT(20) UNSIGNED NOT NULL,
        notification_type VARCHAR(50) NOT NULL,
        subject_id        BIGINT(20) UNSIGNED NOT NULL,
        created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
