<?php

function jm_build_listing_index() {
    global $wpdb;
    $table = jm_get_listing_index_table();

    $wpdb->query("DROP TABLE IF EXISTS {$table}");
    jm_create_listing_index_table();

    $listing_ids = get_posts([
        'post_type'      => 'listing',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $inserted = 0;
    $errors  = [];

    foreach ($listing_ids as $post_id) {
        $name        = get_post_meta($post_id, 'name', true);
        $description = get_post_meta($post_id, 'description', true);
        $city        = get_post_meta($post_id, 'city', true);
        $state       = get_post_meta($post_id, 'state', true);
        $zip_code    = get_post_meta($post_id, 'zip_code', true);
        $verified    = get_post_meta($post_id, 'verified', true);
        $rank        = (int) get_post_meta($post_id, 'rank', true);
        $thumbnail   = get_post_thumbnail_id($post_id);

        if (empty($name) || empty($thumbnail) || empty($city) || empty($state) || empty($zip_code) || empty($description)) {
            continue;
        }

        $result = $wpdb->insert($table, [
            'listing_post_id' => $post_id,
            'city'            => $city,
            'state'           => $state,
            'zip_code'        => $zip_code,
            'lat'             => null,
            'lng'             => null,
            'listing_type'    => 'live_music',
            'verified'        => !empty($verified) ? 1 : 0,
            'search_rank'     => $rank,
        ]);

        if ($result === false) {
            $errors[] = ['post_id' => $post_id, 'error' => $wpdb->last_error];
        } else {
            $inserted++;
        }
    }

    return new WP_REST_Response([
        'processed' => count($listing_ids),
        'inserted'  => $inserted,
        'errors'    => $errors,
    ], 200);
}

function jm_create_listing_index_table() {
    global $wpdb;
    $table = jm_get_listing_index_table();
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
