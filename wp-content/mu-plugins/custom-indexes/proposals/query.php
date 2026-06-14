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

    return array_map('intval', wp_list_pluck($rows, 'listing_id'));
}
