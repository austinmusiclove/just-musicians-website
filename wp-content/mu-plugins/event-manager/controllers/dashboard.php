<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Render the Event Manager admin list page.
 */
function event_manager_render_dashboard() {
    $current_page = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
    $current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';

    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/events';
    $api_url = add_query_arg( array( 'page' => $current_page ), $api_url );

    if ( $current_tab === 'pending-scrape' ) {
        $api_url = add_query_arg( array( 'status' => 'pending-scrape' ), $api_url );
    } elseif ( $current_tab === 'bulk-review' ) {
        $api_url = add_query_arg( array( 'transaction_type' => urlencode( 'multiple' ) ), $api_url );
    } else {
        $api_url = add_query_arg( array( 'transaction_type' => urlencode( 'create,update,delete' ) ), $api_url );
    }

    $response = event_manager_aws_sigv4_request( $api_url );

    $total_count = 0;
    $staged_transactions = array();
    $total_pages = 0;
    $error_msg = '';
    $bulk_result = null;

    // Check for bulk action result
    if ( isset( $_GET['em_bulk_result'] ) ) {
        $bulk_result = get_transient( 'em_bulk_result' );
        delete_transient( 'em_bulk_result' );

        $bulk_error = get_transient( 'em_bulk_error' );
        if ( $bulk_error ) {
            $error_msg = $bulk_error;
            delete_transient( 'em_bulk_error' );
        }
    }

    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            if ( isset( $data['total'] ) ) {
                $total_count = intval( $data['total'] );
                $total_pages = max( 1, intval( $data['total_pages'] ) );
            }

            if ( isset( $data['transactions'] ) && is_array( $data['transactions'] ) ) {
                $staged_transactions = $data['transactions'];
            }
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/dashboard.php';
}
