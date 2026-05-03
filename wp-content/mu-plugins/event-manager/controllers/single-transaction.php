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
    $error_msg = '';
    $approve_result = null;

    // Handle approve action
    if ( isset( $_POST['em_approve'] ) ) {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_approve_' . $transaction_id ) ) {
            wp_die( 'Security check failed.' );
        }

        $approve_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id ) . '/approve';
        $approve_response = event_manager_aws_sigv4_request( $approve_url, 'POST' );

        if ( is_wp_error( $approve_response ) ) {
            $approve_result = array(
                'type'    => 'error',
                'message' => 'API Request failed: ' . $approve_response->get_error_message(),
            );
        } else {
            $response_code = wp_remote_retrieve_response_code( $approve_response );
            $body          = wp_remote_retrieve_body( $approve_response );

            if ( $response_code === 200 || $response_code === 201 ) {
                $approve_result = array(
                    'type'    => 'success',
                    'message' => 'Transaction approved successfully!',
                    'body'    => $body,
                );
            } else {
                $approve_result = array(
                    'type'    => 'error',
                    'message' => 'API Request failed. Status Code: ' . $response_code . '. Response: ' . $body,
                );
            }
        }
    }

    // Handle reject action
    if ( isset( $_POST['em_reject'] ) ) {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_approve_' . $transaction_id ) ) {
            wp_die( 'Security check failed.' );
        }

        $reject_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id ) . '/reject';
        $reject_response = event_manager_aws_sigv4_request( $reject_url, 'POST' );

        if ( is_wp_error( $reject_response ) ) {
            $approve_result = array(
                'type'    => 'error',
                'message' => 'API Request failed: ' . $reject_response->get_error_message(),
            );
        } else {
            $response_code = wp_remote_retrieve_response_code( $reject_response );
            $body          = wp_remote_retrieve_body( $reject_response );

            if ( $response_code === 200 || $response_code === 201 ) {
                $approve_result = array(
                    'type'    => 'success',
                    'message' => 'Transaction rejected successfully!',
                    'body'    => $body,
                );
            } else {
                $approve_result = array(
                    'type'    => 'error',
                    'message' => 'API Request failed. Status Code: ' . $response_code . '. Response: ' . $body,
                );
            }
        }
    }

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
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/single-transaction.php';
}
