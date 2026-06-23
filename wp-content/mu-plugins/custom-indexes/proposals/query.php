<?php

function hm_get_listing_ids_by_event_id($event_id) {
    global $wpdb;
    $table = hm_get_proposal_index_table();

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT listing_id
         FROM {$table}
         WHERE event_id = %d",
        $event_id
    ));

    return array_map('strval', wp_list_pluck($rows, 'listing_id'));
}

function hm_get_proposal_ids_by_event_id($event_id, $args = []) {
    global $wpdb;
    $table = hm_get_proposal_index_table();

    $where_clauses = ["event_id = %d"];
    $params = [$event_id];

    if (!empty($args['status']) && $args['status'] !== 'all') {
        $where_clauses[] = 'status = %s';
        $params[] = $args['status'];
    }

    $where = implode(' AND ', $where_clauses);

    return $wpdb->get_col($wpdb->prepare(
        "SELECT proposal_id FROM {$table} WHERE {$where}",
        $params
    ));
}

function hm_get_proposals_by_listing_ids($listing_ids, $args = []) {
    if (empty($listing_ids)) return [];

    global $wpdb;
    $table = hm_get_proposal_index_table();

    $where_clauses = [];
    $params = [];

    $placeholders = implode(',', array_fill(0, count($listing_ids), '%d'));
    $where_clauses[] = "listing_id IN ({$placeholders})";
    $params = array_merge($params, $listing_ids);

    if (!empty($args['status']) && $args['status'] !== 'all') {
        $where_clauses[] = 'status = %s';
        $params[] = $args['status'];
    }

    $order = 'ASC';
    if (!empty($args['start_date_after'])) {
        $where_clauses[] = 'start_date >= %s';
        $params[] = $args['start_date_after'];
    }
    if (!empty($args['start_date_before'])) {
        $where_clauses[] = 'start_date < %s';
        $params[] = $args['start_date_before'];
        $order = 'DESC';
    }

    $where = implode(' AND ', $where_clauses);

    $sql = $wpdb->prepare(
        "SELECT proposal_id FROM {$table} WHERE {$where} ORDER BY start_date {$order}",
        $params
    );

    //error_log('hm_get_proposals_by_listing_ids SQL: ' . $sql);

    return $wpdb->get_col($sql);
}
