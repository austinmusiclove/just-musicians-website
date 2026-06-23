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
        if ($args['status'] === 'response') {
            $where_clauses[] = 'status != %s';
            $params[] = 'inquiry';
        } else {
            $where_clauses[] = 'status = %s';
            $params[] = $args['status'];
        }
    }

    $where = implode(' AND ', $where_clauses);

    $joins = "LEFT JOIN {$wpdb->posts} proposal_post ON idx.proposal_id = proposal_post.ID";
    $order_by = 'proposal_post.post_modified DESC, idx.proposal_id DESC';

    if (!empty($args['sort'])) {
        if ($args['sort'] === 'high-quote') {
            $joins = "LEFT JOIN {$wpdb->postmeta} quote_meta ON idx.proposal_id = quote_meta.post_id AND quote_meta.meta_key = 'quote'";
            $order_by = 'CAST(COALESCE(quote_meta.meta_value, 0) AS UNSIGNED) DESC, idx.proposal_id DESC';
        } elseif ($args['sort'] === 'low-quote') {
            $joins = "LEFT JOIN {$wpdb->postmeta} quote_meta ON idx.proposal_id = quote_meta.post_id AND quote_meta.meta_key = 'quote'";
            $order_by = "CAST(COALESCE(NULLIF(quote_meta.meta_value, ''), 999999999) AS UNSIGNED) ASC, idx.proposal_id DESC";
        } elseif ($args['sort'] === 'high-draw') {
            $joins = "LEFT JOIN {$wpdb->postmeta} draw_meta ON idx.proposal_id = draw_meta.post_id AND draw_meta.meta_key = 'draw'";
            $order_by = 'CAST(COALESCE(draw_meta.meta_value, 0) AS UNSIGNED) DESC, idx.proposal_id DESC';
        } elseif ($args['sort'] === 'low-draw') {
            $joins = "LEFT JOIN {$wpdb->postmeta} draw_meta ON idx.proposal_id = draw_meta.post_id AND draw_meta.meta_key = 'draw'";
            $order_by = "CAST(COALESCE(NULLIF(draw_meta.meta_value, ''), 999999999) AS UNSIGNED) ASC, idx.proposal_id DESC";
        } elseif ($args['sort'] === 'high-rating') {
            $joins = "LEFT JOIN {$wpdb->postmeta} rating_meta ON idx.listing_id = rating_meta.post_id AND rating_meta.meta_key = 'rating'";
            $order_by = 'CAST(COALESCE(rating_meta.meta_value, 0) AS UNSIGNED) DESC, idx.proposal_id DESC';
        }
    }

    return $wpdb->get_col($wpdb->prepare(
        "SELECT idx.proposal_id FROM {$table} idx {$joins} WHERE {$where} ORDER BY {$order_by}",
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
        if ($args['status'] === 'response') {
            $where_clauses[] = 'status != %s';
            $params[] = 'inquiry';
        } else {
            $where_clauses[] = 'status = %s';
            $params[] = $args['status'];
        }
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
