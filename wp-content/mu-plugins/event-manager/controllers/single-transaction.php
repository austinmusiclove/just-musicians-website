<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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
    $success_msg = '';

    // Check for action result
    if ( isset( $_GET['em_action'] ) && $_GET['em_action'] === 'error' ) {
        $error_data = get_transient( 'em_action_error_' . $transaction_id );
        if ( $error_data ) {
            $error_msg = $error_data;
            delete_transient( 'em_action_error_' . $transaction_id );
        }
    } elseif ( isset( $_GET['em_action'] ) && $_GET['em_action'] === 'success' ) {
        $success_data = get_transient( 'em_action_success_' . $transaction_id );
        if ( $success_data ) {
            $success_msg = $success_data;
            delete_transient( 'em_action_success_' . $transaction_id );
        }
    }

    // Fetch transaction data
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

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/single-transaction.php';
}
