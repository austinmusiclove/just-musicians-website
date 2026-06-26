<?php
/**
 * Event deletion and trash handling
 *
 * @package JustMusicians
 */

function trash_event( $post_id ) {
    $post = get_post( $post_id );

    if ( ! $post || 'event' !== $post->post_type ) {
        return new WP_Error( 'invalid_event', 'Invalid event.', array( 'status' => 400 ) );
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        if ( ! user_owns_event( $post_id ) ) {
            return new WP_Error( 'access_denied', 'You must be the author of the event to delete it' );
        }
    }

    $result = wp_trash_post( $post_id );

    if ( ! $result || is_wp_error( $result ) ) {
        return new WP_Error( 'trash_failed', 'Failed to trash event.', array( 'status' => 500 ) );
    }

    return true;
}

function handle_delete_event( $event_id ) {
    $proposal_ids = hm_get_proposal_ids_by_event_id( $event_id );

    foreach ( $proposal_ids as $proposal_id ) {
        wp_update_post( array(
            'ID'         => (int) $proposal_id,
            'meta_input' => array( 'status' => 'eventremoved' ),
        ), true );

        clear_notifications_by_subject_id( (int) $proposal_id );
    }

    clear_notifications_by_subject_id( (int) $event_id );
}

function handle_event_trashed( $post_id ) {
    if ( get_post_type( $post_id ) !== 'event' ) {
        return;
    }

    handle_delete_event( $post_id );
}

function handle_event_deleted( $post_id ) {
    if ( get_post_type( $post_id ) !== 'event' ) {
        return;
    }

    handle_delete_event( $post_id );
}

add_action( 'trashed_post', 'handle_event_trashed' );
add_action( 'delete_post', 'handle_event_deleted' );
