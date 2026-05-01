<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Render the Event Manager admin page.
 */
function event_manager_render_admin_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
    $transaction_id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';

    // Single Transaction View
    if ( 'view' === $action && ! empty( $transaction_id ) ) {
        $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id );
        $response = event_manager_aws_sigv4_request( $api_url );

        $transaction = null;
        $error_msg = '';

        if ( is_wp_error( $response ) ) {
            $error_msg = $response->get_error_message();
        } else {
            $response_code = wp_remote_retrieve_response_code( $response );
            $body = wp_remote_retrieve_body( $response );

            if ( $response_code === 200 ) {
                $data = json_decode( $body, true );
                if ( isset( $data['staged_data'] ) ) {
                    $transaction = $data['staged_data'];
                    // Merge the ID into the transaction data for convenience
                    $transaction['id'] = $transaction_id;
                } else {
                    $error_msg = 'API response did not contain "staged_data".';
                }
            } else {
                $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
            }
        }

        include plugin_dir_path( dirname( __FILE__ ) ) . 'views/single-transaction.php';
        return;
    }

    // Default List View
    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/events';
    $response = event_manager_aws_sigv4_request( $api_url );

    $staged_count = 0;
    $staged_transactions = array();
    $error_msg = '';

    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            if ( isset( $data['count'] ) ) {
                $staged_count = intval( $data['count'] );
            } else {
                $error_msg = 'API response did not contain a "count" field.';
            }

            if ( isset( $data['transactions'] ) && is_array( $data['transactions'] ) ) {
                $staged_transactions = $data['transactions'];
            }
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    // Include the view for rendering
    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/admin-page.php';
}
