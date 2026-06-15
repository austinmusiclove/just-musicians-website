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

function hm_get_proposals_by_listing_ids($listing_ids) {
    if (empty($listing_ids)) return [];

    global $wpdb;
    $table = hm_get_proposal_index_table();

    $placeholders = implode(',', array_fill(0, count($listing_ids), '%d'));
    return $wpdb->get_col($wpdb->prepare(
        "SELECT proposal_id FROM {$table} WHERE listing_id IN ({$placeholders})",
        $listing_ids
    ));
}
