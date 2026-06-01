<?php

function jm_get_listing_ids_by_bounds($args = []) {
    global $wpdb;
    $table = jm_get_listing_index_table();

    $args = wp_parse_args($args, [
        'lat_min'      => null,
        'lat_max'      => null,
        'lng_min'      => null,
        'lng_max'      => null,
        'verified'     => null,
        'listing_type' => null,
    ]);

    $where  = ['lat BETWEEN %f AND %f', 'lng BETWEEN %f AND %f'];
    $values = [$args['lat_min'], $args['lat_max'], $args['lng_min'], $args['lng_max']];

    if ($args['verified'] !== null) {
        $where[]  = 'verified = %d';
        $values[] = $args['verified'] ? 1 : 0;
    }

    if ($args['listing_type'] !== null) {
        $where[]  = 'listing_type = %s';
        $values[] = $args['listing_type'];
    }

    $sql = $wpdb->prepare(
        "SELECT listing_post_id, MAX(rank) AS max_rank
         FROM {$table}
         WHERE " . implode(' AND ', $where) . "
         GROUP BY listing_post_id
         ORDER BY max_rank DESC, listing_post_id ASC",
        $values
    );

    return array_map('intval', wp_list_pluck($wpdb->get_results($sql), 'listing_post_id'));
}

function jm_get_listing_ids_by_location($args = []) {
    global $wpdb;
    $table = jm_get_listing_index_table();

    $args = wp_parse_args($args, [
        'city'         => null,
        'state'        => null,
        'verified'     => null,
        'listing_type' => null,
    ]);

    $where  = [];
    $values = [];

    if ($args['city'] !== null) {
        $where[]  = 'city = %s';
        $values[] = $args['city'];
    }

    if ($args['state'] !== null) {
        $where[]  = 'state = %s';
        $values[] = $args['state'];
    }

    if ($args['verified'] !== null) {
        $where[]  = 'verified = %d';
        $values[] = $args['verified'] ? 1 : 0;
    }

    if ($args['listing_type'] !== null) {
        $where[]  = 'listing_type = %s';
        $values[] = $args['listing_type'];
    }

    $where_sql = $where ? implode(' AND ', $where) : '1=1';

    $sql = $wpdb->prepare(
        "SELECT listing_post_id, MAX(rank) AS max_rank
         FROM {$table}
         WHERE {$where_sql}
         GROUP BY listing_post_id
         ORDER BY max_rank DESC, listing_post_id ASC",
        $values
    );

    return array_map('intval', wp_list_pluck($wpdb->get_results($sql), 'listing_post_id'));
}
