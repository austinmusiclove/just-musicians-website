<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'transaction-approve.php';
require_once plugin_dir_path( __FILE__ ) . 'transaction-reject.php';
require_once plugin_dir_path( __FILE__ ) . 'transaction-update.php';

function event_manager_process_actions() {
    if ( ! is_admin() ) {
        return;
    }

    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'event-manager' ) {
        return;
    }

    if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'view' ) {
        return;
    }

    if ( empty( $_GET['id'] ) ) {
        return;
    }

    $transaction_id = sanitize_text_field( $_GET['id'] );

    $action = null;
    if ( isset( $_POST['em_update'] ) ) {
        $action = 'update';
    } elseif ( isset( $_POST['em_approve'] ) ) {
        $action = 'approve';
    } elseif ( isset( $_POST['em_reject'] ) ) {
        $action = 'reject';
    }

    if ( $action === null ) {
        return;
    }

    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_approve_' . $transaction_id ) ) {
        wp_die( 'Security check failed.' );
    }

    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id );
    $response = event_manager_aws_sigv4_request( $api_url );

    if ( is_wp_error( $response ) ) {
        set_transient( 'em_action_error_' . $transaction_id, 'API request failed: ' . $response->get_error_message(), 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );

    if ( $response_code !== 200 ) {
        set_transient( 'em_action_error_' . $transaction_id, 'API Request failed. Status Code: ' . $response_code . '. Response: ' . $body, 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }

    $data = json_decode( $body, true );
    $transaction = $data;
    $transaction['id'] = $transaction_id;

    $result = null;

    if ( $action === 'update' ) {
        $result = event_manager_handle_update( $transaction_id, $transaction );
    } elseif ( $action === 'approve' ) {
        $result = event_manager_handle_approve( $transaction_id );
    } elseif ( $action === 'reject' ) {
        $result = event_manager_handle_reject( $transaction_id );
    }

    if ( $result !== null && $result['type'] === 'success' ) {
        if ( $action === 'update' ) {
            set_transient( 'em_action_success_' . $transaction_id, $result['message'], 30 );
            wp_redirect( admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $transaction_id ) . '&em_action=success' ) );
            exit;
        } else {
            $next_id = ! empty( $transaction['next_transaction_id'] ) ? $transaction['next_transaction_id'] : null;
            $redirect_url = $next_id
                ? admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $next_id ) )
                : admin_url( 'admin.php?page=event-manager' );
            wp_redirect( $redirect_url );
            exit;
        }
    }

    if ( $result !== null && $result['type'] === 'error' ) {
        set_transient( 'em_action_error_' . $transaction_id, $result['message'], 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }
}
add_action( 'admin_init', 'event_manager_process_actions' );
