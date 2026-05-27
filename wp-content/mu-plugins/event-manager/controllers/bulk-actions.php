<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function event_manager_process_bulk_actions() {
    if ( ! is_admin() ) {
        return;
    }

    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'event-manager' ) {
        return;
    }

    if ( isset( $_GET['action'] ) && $_GET['action'] === 'view' ) {
        return;
    }

    if ( ! isset( $_POST['em_bulk_action'] ) || $_POST['em_bulk_action'] !== 'reject' ) {
        return;
    }

    $tab_param = '';
    if ( isset( $_POST['tab'] ) && in_array( $_POST['tab'], array( 'pending-scrape', 'bulk-review' ), true ) ) {
        $tab_param = '&tab=' . sanitize_key( $_POST['tab'] );
    }

    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_bulk_reject' ) ) {
        wp_die( 'Security check failed.' );
    }

    if ( empty( $_POST['em_transactions'] ) || ! is_array( $_POST['em_transactions'] ) ) {
        set_transient( 'em_bulk_error', 'No transactions selected.', 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&em_bulk_result=error' . $tab_param ) );
        exit;
    }

    $transaction_ids = array_map( 'sanitize_text_field', $_POST['em_transactions'] );

    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/reject';
    $body = wp_json_encode( array( 'ids' => $transaction_ids ) );
    $response = event_manager_aws_sigv4_request( $api_url, 'POST', $body );

    if ( is_wp_error( $response ) ) {
        set_transient( 'em_bulk_error', 'API request failed: ' . $response->get_error_message(), 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&em_bulk_result=error' . $tab_param ) );
        exit;
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    $resp_body = wp_remote_retrieve_body( $response );

    if ( $response_code !== 200 ) {
        set_transient( 'em_bulk_error', 'API request failed. Status Code: ' . $response_code . '. Response: ' . $resp_body, 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&em_bulk_result=error' . $tab_param ) );
        exit;
    }

    $data = json_decode( $resp_body, true );
    $total = isset( $data['total'] ) ? intval( $data['total'] ) : 0;
    $rejected = isset( $data['rejected'] ) ? intval( $data['rejected'] ) : 0;
    $failed = isset( $data['failed'] ) ? intval( $data['failed'] ) : 0;

    if ( $rejected > 0 && $failed > 0 ) {
        $result_type = 'partial';
    } elseif ( $rejected > 0 ) {
        $result_type = 'success';
    } else {
        $result_type = 'error';
    }

    set_transient( 'em_bulk_result', array(
        'type'     => $result_type,
        'total'    => $total,
        'rejected' => $rejected,
        'failed'   => $failed,
    ), 30 );

    wp_redirect( admin_url( 'admin.php?page=event-manager&em_bulk_result=' . $result_type . $tab_param ) );
    exit;
}
add_action( 'admin_init', 'event_manager_process_bulk_actions' );
