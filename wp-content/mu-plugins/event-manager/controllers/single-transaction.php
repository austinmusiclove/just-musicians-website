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
                $transaction['id'] = $transaction_id;
            } else {
                $error_msg = 'API response did not contain "staged_data".';
            }
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/single-transaction.php';
}
