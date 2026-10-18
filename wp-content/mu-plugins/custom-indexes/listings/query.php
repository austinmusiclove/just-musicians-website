<?php

// when postal codes are passed in, the results will rank any listings matching those postal codes at the top
function hm_get_listing_ids_by_bounds($args = []) {
    global $wpdb;
    $table = hm_get_listing_index_table();

    $args = wp_parse_args($args, [
        'lat_min'          => null,
        'lat_max'          => null,
        'lng_min'          => null,
        'lng_max'          => null,
        'verified'         => null,
        'listing_type'     => null,
        'postal_codes'     => null,
        'priority_listings' => null,
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

    $order = 'max_rank DESC, listing_post_id ASC';

    // When postal codes are passed in, the results will rank any listings matching those postal codes at the top.
    // Processed first so that priority listings (added after) take precedence over postal codes.
    if (!empty($args['postal_codes']) && is_array($args['postal_codes'])) {
        $postal_codes = array_values(array_filter($args['postal_codes']));
        if ($postal_codes) {
            $pc_placeholders = implode(', ', array_fill(0, count($postal_codes), 'UPPER(%s)'));
            $order = "CASE WHEN UPPER(zip_code) IN ({$pc_placeholders}) THEN 0 ELSE 1 END ASC, {$order}";
            $values = array_merge($values, $postal_codes);
        }
    }

    // When priority listings are passed in, sort those IDs first (in the order provided), ahead of postal codes.
    // Uses inline int literals so it adds no placeholders to keep $values aligned with the SQL.
    if (!empty($args['priority_listings']) && is_array($args['priority_listings'])) {
        $priority_ids = array_values(array_unique(array_filter(array_map('intval', $args['priority_listings']))));
        if ($priority_ids) {
            $whens = [];
            foreach ($priority_ids as $index => $pid) {
                $whens[] = "WHEN {$pid} THEN {$index}";
            }
            $else = count($priority_ids);
            $order = "CASE listing_post_id " . implode(' ', $whens) . " ELSE {$else} END ASC, {$order}";
        }
    }

    $sql = $wpdb->prepare(
        "SELECT listing_post_id, MAX(search_rank) AS max_rank
         FROM {$table}
         WHERE " . implode(' AND ', $where) . "
         GROUP BY listing_post_id
         ORDER BY {$order}",
        $values
    );

    return array_map('intval', wp_list_pluck($wpdb->get_results($sql), 'listing_post_id'));
}

function hm_get_listing_ids_by_distance($lat, $lng, $distance_miles, $verified = null, $listing_type = null, $postal_codes = null, $priority_listings = null) {
    $lat_delta = $distance_miles / 69;
    $lng_delta = $distance_miles / (69 * cos(deg2rad($lat)));

    return hm_get_listing_ids_by_bounds([
        'lat_min'          => $lat - $lat_delta,
        'lat_max'          => $lat + $lat_delta,
        'lng_min'          => $lng - $lng_delta,
        'lng_max'          => $lng + $lng_delta,
        'verified'         => $verified,
        'listing_type'     => $listing_type,
        'postal_codes'     => $postal_codes,
        'priority_listings' => $priority_listings,
    ]);
}

function hm_get_listing_ids_by_location($args = []) {
    global $wpdb;
    $table = hm_get_listing_index_table();

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
        "SELECT listing_post_id, MAX(search_rank) AS max_rank
         FROM {$table}
         WHERE {$where_sql}
         GROUP BY listing_post_id
         ORDER BY max_rank DESC, listing_post_id ASC",
        $values
    );

    return array_map('intval', wp_list_pluck($wpdb->get_results($sql), 'listing_post_id'));
}
