<?php

add_action('save_post_listing', 'hm_index_listing_on_save', 20, 3);
add_action('wp_trash_post',        'hm_index_listing_on_trash');
add_action('untrash_post',      'hm_index_listing_on_untrash');
function hm_index_upsert_listing($post_id) {
    global $wpdb;
    $table = hm_get_listing_index_table();

    $name        = get_post_meta($post_id, 'name', true);
    $description = get_post_meta($post_id, 'description', true);
    $city        = get_post_meta($post_id, 'city', true);
    $state       = get_post_meta($post_id, 'state', true);
    $zip_code    = get_post_meta($post_id, 'zip_code', true);
    $verified    = get_post_meta($post_id, 'verified', true);
    $rank        = (int) get_post_meta($post_id, 'rank', true);
    $thumbnail   = get_post_thumbnail_id($post_id);

    $wpdb->delete($table, ['listing_post_id' => $post_id]);

    if (empty($name) || empty($thumbnail) || empty($city) || empty($state) || empty($zip_code) || empty($description)) {
        return 'incomplete';
    }

    $loc = hm_location_get_by_pc($zip_code);

    $result = $wpdb->insert($table, [
        'listing_post_id' => $post_id,
        'city'            => $city,
        'state'           => $state,
        'zip_code'        => $zip_code,
        'lat'             => $loc ? $loc->lat : null,
        'lng'             => $loc ? $loc->lng : null,
        'listing_type'    => 'live_music',
        'verified'        => !empty($verified) ? 1 : 0,
        'search_rank'     => $rank,
    ]);

    if ($result === false) {
        return 'error';
    }

    return 'inserted';
}

function hm_index_listing_on_save($post_id, $post, $update) {
    if ($post->post_status === 'auto-draft') { return; }
    if ($post->post_status !== 'publish') { return; }

    hm_index_upsert_listing($post_id);
}

function hm_index_listing_on_trash($post_id) {
    if (get_post_type($post_id) !== 'listing') { return; }

    global $wpdb;
    $table = hm_get_listing_index_table();

    $wpdb->delete($table, ['listing_post_id' => $post_id]);
}

function hm_index_listing_on_untrash($post_id) {
    if (get_post_type($post_id) !== 'listing') { return; }

    $post = get_post($post_id);
    if ($post && $post->post_status === 'publish') {
        hm_index_upsert_listing($post_id);
    }
}

function hm_index_listing_on_rank_change($post_id, $rank) {
    global $wpdb;
    $table = hm_get_listing_index_table();

    $wpdb->update($table, ['search_rank' => (int) $rank], ['listing_post_id' => $post_id]);
}
