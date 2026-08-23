<?php
if (!defined('ABSPATH')) { exit; }

function hm_create_access_index_table() {
    global $wpdb;
    $table = hm_get_access_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id          BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id     BIGINT(20) UNSIGNED NOT NULL,
        subject_id  VARCHAR(191) NOT NULL,
        access_type VARCHAR(50) NOT NULL,
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_subject_type (user_id, subject_id, access_type),
        INDEX idx_subject_type (subject_id, access_type)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
