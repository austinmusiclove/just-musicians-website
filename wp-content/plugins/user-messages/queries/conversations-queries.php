<?php

function get_conversations_query($tables, $user_id, $cursor_time, $user_listing_ids, $inquiry_listing_ids, $get_newer, $limit) {
    $query = "
        SELECT c.id AS conversation_id,
               m.content,
               m.created_at,
               m.sender_id,
               m.id AS message_id,
               IF(rr.user_id IS NULL, 0, 1) AS is_read
        FROM {$tables['conversations']} c
        JOIN {$tables['conversation_participants']} cp
            ON cp.conversation_id = c.id
        LEFT JOIN {$tables['messages']} m
            ON m.id = (
                SELECT id FROM {$tables['messages']}
                WHERE conversation_id = c.id
                ORDER BY created_at DESC LIMIT 1
            )
        LEFT JOIN {$tables['read_receipts']} rr
            ON rr.message_id = m.id AND rr.user_id = %d ";
    $params = [$user_id];

    $where_clause = get_conversations_query_where($tables, $user_id, $cursor_time, $user_listing_ids, $inquiry_listing_ids, $get_newer);
    $query .= $where_clause['sql'];
    $params = array_merge($params, $where_clause['args']);

    $query .= "
        ORDER BY c.updated_at DESC
        LIMIT %d";
    $params[] = $limit;
    return [
        'sql'  => $query,
        'args' => $params,
    ];
}

function get_conversations_query_where($tables, $user_id, $cursor_time, $user_listing_ids, $inquiry_listing_ids, $get_newer) {
    $where = "WHERE 1 = 1 ";
    $params = [];

    // Cursor
    $cursor_comparison = $get_newer ? '>' : '<';
    if ($cursor_time) {
        $where .= "AND m.created_at {$cursor_comparison} %s ";
        $params[] = $cursor_time;
    }

    // Inquiry responses
    if (!empty($inquiry_listing_ids)) {
        $placeholders = implode(',', array_fill(0, count($inquiry_listing_ids), '%d'));
        $where .= "
            AND c.id IN (
                SELECT cp.conversation_id
                FROM {$tables['conversation_participants']} cp
                WHERE cp.user_id = %d OR cp.listing_id IN ({$placeholders})
                GROUP BY cp.conversation_id
                HAVING
                    SUM(cp.user_id = %d) > 0 AND
                    SUM(cp.listing_id IN ({$placeholders})) > 0
            ) ";
        $params[] = $user_id;
        $params = array_merge($params, $inquiry_listing_ids);
        $params[] = $user_id;
        $params = array_merge($params, $inquiry_listing_ids);
    }

    // All user conversations
    if (empty($inquiry_listing_ids) and !empty($user_listing_ids)) {
        $placeholders = implode(',', array_fill(0, count($user_listing_ids), '%d'));
        $where .= " AND ( cp.user_id = %d OR cp.listing_id IN ({$placeholders}) ) ";
        $params[] = $user_id;
        $params = array_merge($params, $user_listing_ids);
    }
    if (empty($inquiry_listing_ids) and empty($user_listing_ids)) {
        $where .= " AND cp.user_id = %d ";
        $params[] = $user_id;
    }

    // cursor
    return [
        'sql'  => $where,
        'args' => $params,
    ];
}
