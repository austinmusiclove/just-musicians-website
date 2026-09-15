<?php
if (!defined('ABSPATH')) { exit; }

function hm_build_access_index() {
    //global $wpdb;
    //$table = hm_get_access_table();
    //$wpdb->query("DROP TABLE IF EXISTS {$table}");

    hm_create_access_index_table();

    $processed = 0;
    $inserted  = 0;
    $errors    = [];

    foreach (hm_access_post_types() as $post_type) {
        $post_ids = get_posts([
            'post_type'      => $post_type,
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($post_ids as $post_id) {
            $result = hm_access_grant_author_access($post_id);
            $processed++;

            if (is_wp_error($result)) {
                $errors[] = ['post_id' => $post_id, 'post_type' => $post_type, 'error' => $result->get_error_message()];
            } else {
                $inserted += $result;
            }
        }
    }

    return new WP_REST_Response([
        'processed' => $processed,
        'inserted'  => $inserted,
        'errors'    => $errors,
    ], 200);
}

function hm_create_access_index_table() {
    global $wpdb;
    $table = hm_get_access_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id           BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id      BIGINT(20) UNSIGNED NULL,
        email        VARCHAR(191) NULL,
        subject_id   VARCHAR(191) NOT NULL,
        subject_type VARCHAR(50) NOT NULL,
        access_type  VARCHAR(50) NOT NULL,
        created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_subject (user_id, subject_type, subject_id),
        UNIQUE KEY uq_email_subject (email, subject_type, subject_id),
        INDEX idx_subject_type (subject_id, access_type),
        INDEX idx_user_id_subject_type (user_id, subject_type),
        INDEX idx_email (email)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
