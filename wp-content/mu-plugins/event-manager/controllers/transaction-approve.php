<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle transaction approve action.
 *
 * @param string $transaction_id
 * @return array|null Result array with 'type', 'message', 'body' keys, or null if not triggered.
 */
function event_manager_handle_approve( $transaction_id ) {
    if ( ! isset( $_POST['em_approve'] ) ) {
        return null;
    }

    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_approve_' . $transaction_id ) ) {
        wp_die( 'Security check failed.' );
    }

    // Build the request body from submitted form data
    $staged_data = isset( $_POST['staged'] ) && is_array( $_POST['staged'] ) ? $_POST['staged'] : array();
    $body = ! empty( $staged_data ) ? wp_json_encode( $staged_data ) : '';

    $approve_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id ) . '/approve';
    $response = event_manager_aws_sigv4_request( $approve_url, 'POST', $body );

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
            'message' => 'Transaction approved successfully!',
            'body'    => $body,
        );
    }

    return array(
        'type'    => 'error',
        'message' => 'API Request failed. Status Code: ' . $response_code . '. Response: ' . $body,
    );
}
