<?php

add_action('save_post_proposal', 'hm_index_proposal_on_save', 20, 3);
add_action('wp_trash_post',      'hm_index_proposal_on_trash');
add_action('untrash_post',       'hm_index_proposal_on_untrash');
add_action('added_post_meta',    'hm_index_proposal_on_event_start_date_change', 20, 4);
add_action('updated_post_meta',  'hm_index_proposal_on_event_start_date_change', 20, 4);

function hm_index_upsert_proposal($post_id) {
    global $wpdb;
    $table = hm_get_proposal_index_table();

    $event_id   = (int) get_post_meta($post_id, 'event', true);
    $listing_id = (int) get_post_meta($post_id, 'listing', true);
    $status     = get_post_meta($post_id, 'status', true);
    $start_date = get_post_meta($event_id, 'start_date', true);
    $start_date = $start_date ?: '0000-00-00';

    if (!$event_id || !$listing_id) {
        return 'incomplete';
    }

    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO {$table} (event_id, listing_id, proposal_id, status, start_date)
         VALUES (%d, %d, %d, %s, %s)
         ON DUPLICATE KEY UPDATE status = VALUES(status), proposal_id = VALUES(proposal_id), start_date = VALUES(start_date)",
        $event_id,
        $listing_id,
        $post_id,
        $status ?: 'requested',
        $start_date
    ));

    if ($result === false) {
        return 'error';
    }

    return 'inserted';
}

function hm_index_proposal_on_save($post_id, $post, $update) {
    if ($post->post_status === 'auto-draft') { return; }
    if ($post->post_status !== 'publish') { return; }

    hm_index_upsert_proposal($post_id);
}

function hm_index_proposal_on_trash($post_id) {
    if (get_post_type($post_id) !== 'proposal') { return; }

    global $wpdb;
    $table = hm_get_proposal_index_table();

    $event_id   = (int) get_post_meta($post_id, 'event', true);
    $listing_id = (int) get_post_meta($post_id, 'listing', true);

    if ($event_id && $listing_id) {
        $wpdb->delete($table, [
            'event_id'   => $event_id,
            'listing_id' => $listing_id,
        ]);
    }
}

function hm_index_proposal_on_untrash($post_id) {
    if (get_post_type($post_id) !== 'proposal') { return; }

    hm_index_upsert_proposal($post_id);
}

function hm_index_proposal_on_event_start_date_change($meta_id, $post_id, $meta_key, $meta_value) {
    if ($meta_key !== 'start_date') { return; }
    if (get_post_type($post_id) !== 'event') { return; }

    global $wpdb;
    $table = hm_get_proposal_index_table();

    $start_date = $meta_value ?: '0000-00-00';

    $wpdb->update(
        $table,
        ['start_date' => $start_date],
        ['event_id'   => (int) $post_id],
        ['%s'],
        ['%d']
    );
}
