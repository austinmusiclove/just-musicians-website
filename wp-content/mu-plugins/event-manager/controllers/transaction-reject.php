<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle transaction reject action.
 *
 * @param string $transaction_id
 * @return array|null Result array with 'type', 'message', 'body' keys, or null if not triggered.
 */
function event_manager_handle_reject( $transaction_id ) {
    if ( ! isset( $_POST['em_reject'] ) ) {
        return null;
    }

    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_approve_' . $transaction_id ) ) {
        wp_die( 'Security check failed.' );
    }

    $reject_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id ) . '/reject';
    $response = event_manager_aws_sigv4_request( $reject_url, 'POST' );

    if ( is_wp_error( $response ) ) {
        return array(
            'type'    => 'error',
            'message' => 'API Request failed: ' . $response->get_error_message(),
        );
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    $body          = wp_remote_retrieve_body( $response );

    if ( $response_code === 200 || $response_code === 201 ) {
        return array(
            'type'    => 'success',
            'message' => 'Transaction rejected successfully!',
            'body'    => $body,
        );
    }

    return array(
        'type'    => 'error',
        'message' => 'API Request failed. Status Code: ' . $response_code . '. Response: ' . $body,
    );
}
