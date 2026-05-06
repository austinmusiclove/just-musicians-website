<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'transaction-approve.php';
require_once plugin_dir_path( __FILE__ ) . 'transaction-reject.php';
require_once plugin_dir_path( __FILE__ ) . 'transaction-update.php';

/**
 * Render the single transaction detail page.
 *
 * @param string $transaction_id The transaction ID to display.
 */
function event_manager_render_single_transaction( $transaction_id ) {
    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id );
    $response = event_manager_aws_sigv4_request( $api_url );

    $transaction = null;
    $staged = array();
    $current = array();
    $error_msg = '';
    $approve_result = null;

    // Fetch transaction data first (needed for update handler)
    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            $transaction = $data;
            $transaction['id'] = $transaction_id;
            $staged = isset( $data['staged_data'] ) ? $data['staged_data'] : array();
            $current = isset( $data['current_data'] ) ? $data['current_data'] : array();
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    // Handle update action (only if we have transaction data)
    if ( $error_msg === '' && $transaction !== null ) {
        $approve_result = event_manager_handle_update( $transaction_id, $transaction );
    }

    // Handle approve action (only if update wasn't triggered)
    if ( $approve_result === null && $error_msg === '' ) {
        $approve_result = event_manager_handle_approve( $transaction_id );
    }

    // Handle reject action (only if update and approve weren't triggered)
    if ( $approve_result === null && $error_msg === '' ) {
        $approve_result = event_manager_handle_reject( $transaction_id );
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/single-transaction.php';
}
