<?php

function hm_build_listing_index() {
    global $wpdb;
    $table = hm_get_listing_index_table();

    $wpdb->query("DROP TABLE IF EXISTS {$table}");
    hm_create_listing_index_table();

    $listing_ids = get_posts([
        'post_type'      => 'listing',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $inserted   = 0;
    $incomplete = 0;
    $errors     = [];

    foreach ($listing_ids as $post_id) {
        $status = hm_index_upsert_listing($post_id);

        if ($status === 'inserted') {
            $inserted++;
        } elseif ($status === 'incomplete') {
            $incomplete++;
        } else {
            $errors[] = ['post_id' => $post_id, 'error' => 'Insert failed'];
        }
    }

    return new WP_REST_Response([
        'processed'  => count($listing_ids),
        'inserted'   => $inserted,
        'incomplete' => $incomplete,
        'errors'     => $errors,
    ], 200);
}

function hm_create_listing_index_table() {
    global $wpdb;
    $table = hm_get_listing_index_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id              BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        listing_post_id BIGINT(20) UNSIGNED NOT NULL,
        city            VARCHAR(255) DEFAULT '',
        state           VARCHAR(100) DEFAULT '',
        zip_code        VARCHAR(20) DEFAULT '',
        lat             DECIMAL(10,7) DEFAULT NULL,
        lng             DECIMAL(11,7) DEFAULT NULL,
        listing_type    VARCHAR(50) NOT NULL DEFAULT 'live_music',
        verified        TINYINT(1) NOT NULL DEFAULT 0,
        search_rank     INT NOT NULL DEFAULT 0,
        created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_lat_lng (lat, lng),
        INDEX idx_city_state (city, state),
        INDEX idx_search_rank_id (search_rank, listing_post_id)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
