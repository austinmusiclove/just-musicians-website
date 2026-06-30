<?php
/**
 * Application deletion and trash handling
 *
 * @package JustMusicians
 */

function trash_application( $post_id ) {
    $post = get_post( $post_id );

    if ( ! $post || 'application' !== $post->post_type ) {
        return new WP_Error( 'invalid_application', 'Invalid application.', array( 'status' => 400 ) );
    }

    $auth = user_can_delete_application($post_id);
    if (is_wp_error($auth)) {
        return $auth;
    }

    $result = wp_trash_post( $post_id );
    if ( ! $result || is_wp_error( $result ) ) {
        return new WP_Error( 'trash_failed', 'Failed to trash application.', array( 'status' => 500 ) );
    }

    return true;
}
