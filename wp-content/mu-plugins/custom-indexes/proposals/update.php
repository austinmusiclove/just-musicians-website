<?php

add_action('save_post_proposal', 'hm_index_proposal_on_save', 20, 3);
add_action('wp_trash_post',      'hm_index_proposal_on_trash');
add_action('untrash_post',       'hm_index_proposal_on_untrash');

function hm_index_upsert_proposal($post_id) {
    global $wpdb;
    $table = hm_get_proposal_index_table();

    $event_id   = (int) get_post_meta($post_id, 'event', true);
    $listing_id = (int) get_post_meta($post_id, 'listing', true);
    $status     = get_post_meta($post_id, 'status', true);

    if (!$event_id || !$listing_id) {
        return 'incomplete';
    }

    $result = $wpdb->replace($table, [
        'event_id'   => $event_id,
        'listing_id' => $listing_id,
        'status'     => $status ?: 'requested',
    ]);

    if ($result === false) {
        return 'error';
    }

    return 'inserted';
}

function hm_index_proposal_on_save($post_id, $post, $update) {
    error_log('save');
    if ($post->post_status === 'auto-draft') { return; }

    hm_index_upsert_proposal($post_id);
}

function hm_index_proposal_on_trash($post_id) {
    error_log('trash');
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
    error_log('untrash');
    if (get_post_type($post_id) !== 'proposal') { return; }

    hm_index_upsert_proposal($post_id);
}
