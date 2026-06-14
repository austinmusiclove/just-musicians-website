<?php

function hm_build_proposal_index() {
    global $wpdb;
    $table = hm_get_proposal_index_table();

    $wpdb->query("DROP TABLE IF EXISTS {$table}");
    hm_create_proposal_index_table();

    $proposal_ids = get_posts([
        'post_type'      => 'proposal',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $inserted = 0;
    $errors   = [];

    foreach ($proposal_ids as $post_id) {
        $status = hm_index_upsert_proposal($post_id);

        if ($status === 'inserted') {
            $inserted++;
        } else {
            $errors[] = ['post_id' => $post_id, 'error' => $status];
        }
    }

    return new WP_REST_Response([
        'processed' => count($proposal_ids),
        'inserted'  => $inserted,
        'errors'    => $errors,
    ], 200);
}

function hm_create_proposal_index_table() {
    global $wpdb;
    $table = hm_get_proposal_index_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id          BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        event_id    BIGINT(20) UNSIGNED NOT NULL,
        listing_id  BIGINT(20) UNSIGNED NOT NULL,
        status      VARCHAR(50) NOT NULL DEFAULT 'requested',
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_event_id (event_id),
        INDEX idx_listing_id (listing_id),
        UNIQUE KEY uq_event_listing (event_id, listing_id)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
