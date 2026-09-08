<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function delete_collection($collection_id) {
    if (empty($collection_id)) {
        return new WP_Error('missing_post_id', 'Cannot delete collection without post ID.', ['status' => 400]);
    }

    $post_id = intval($collection_id);
    $post = get_post($post_id);

    if (!$post) {
        return new WP_Error('not_found', 'Collection not found.', ['status' => 404]);
    }

    if ($post->post_type !== 'collection') {
        return new WP_Error('invalid_type', 'Post is not a collection.', ['status' => 400]);
    }

    $result = wp_trash_post($post_id);

    if (!$result || is_wp_error($result)) {
        return new WP_Error('delete_failed', 'Failed to delete collection.', ['status' => 500]);
    }

    return true;
}
