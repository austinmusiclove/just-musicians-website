<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function event_manager_process_multiple_selected() {
    if ( ! is_admin() ) {
        return;
    }

    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'event-manager' ) {
        return;
    }

    if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'multi-view' ) {
        return;
    }

    if ( empty( $_GET['id'] ) ) {
        return;
    }

    $is_approve = isset( $_POST['em_bulk_approve_selected'] );
    $is_reject  = isset( $_POST['em_bulk_reject_selected'] );

    if ( ! $is_approve && ! $is_reject ) {
        return;
    }

    $transaction_id = sanitize_text_field( $_GET['id'] );

    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'em_bulk_approve_' . $transaction_id ) ) {
        wp_die( 'Security check failed.' );
    }

    if ( empty( $_POST['selected_indices'] ) || ! is_array( $_POST['selected_indices'] ) ) {
        set_transient( 'em_action_error_' . $transaction_id, 'No transactions selected.', 30 );
        wp_redirect( admin_url( 'admin.php?page=event-manager&action=multi-view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }

    $selected_indices = array_map( 'intval', $_POST['selected_indices'] );
    $api_base = untrailingslashit( AWS_API_BASE_URL );

    if ( $is_approve ) {
        $approve_items = array();
        foreach ( $selected_indices as $index ) {
            if ( ! isset( $_POST['transactions'][ $index ] ) ) {
                continue;
            }
            $txn = wp_unslash( $_POST['transactions'][ $index ] );
            $staged = isset( $txn['staged'] ) ? $txn['staged'] : array();

            foreach ( $staged as $key => $value ) {
                if ( $value === '' || $value === 'null' ) {
                    $staged[ $key ] = null;
                }
            }

            $approve_items[] = array(
                'staged_transaction_id' => isset( $txn['staged_transaction_id'] ) ? $txn['staged_transaction_id'] : null,
                'override_data'         => $staged,
            );
        }

        $body     = wp_json_encode( $approve_items );
        $api_url  = $api_base . '/staged-transactions/approve';
        $response = event_manager_aws_sigv4_request( $api_url, 'POST', $body );

        if ( is_wp_error( $response ) ) {
            set_transient( 'em_action_error_' . $transaction_id, 'Approve request failed: ' . $response->get_error_message(), 30 );
            wp_redirect( admin_url( 'admin.php?page=event-manager&action=multi-view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
            exit;
        }

        $response_code = wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $data          = json_decode( $response_body, true );

        if ( $response_code === 200 && isset( $data['results'] ) ) {
            $success_count = 0;
            $fail_count    = 0;
            $messages      = array();

            foreach ( $data['results'] as $result ) {
                if ( ! empty( $result['success'] ) ) {
                    $success_count++;
                } else {
                    $fail_count++;
                    $messages[] = isset( $result['error'] ) ? $result['error'] : 'Unknown error';
                }
            }

            if ( $fail_count === 0 ) {
                set_transient( 'em_action_success_' . $transaction_id, $success_count . ' event(s) approved successfully.', 30 );
                wp_redirect( admin_url( 'admin.php?page=event-manager&tab=bulk-review' ) );
                exit;
            }

            $error_msg = $success_count . ' approved, ' . $fail_count . ' failed: ' . implode( '; ', $messages );
            set_transient( 'em_action_error_' . $transaction_id, $error_msg, 30 );
        } else {
            set_transient( 'em_action_error_' . $transaction_id, 'Approve request failed. Status Code: ' . $response_code . '. Response: ' . $response_body, 30 );
        }

        wp_redirect( admin_url( 'admin.php?page=event-manager&action=multi-view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }

    if ( $is_reject ) {
        $ids = array();
        foreach ( $selected_indices as $index ) {
            if ( isset( $_POST['transactions'][ $index ]['staged_transaction_id'] ) ) {
                $ids[] = $_POST['transactions'][ $index ]['staged_transaction_id'];
            }
        }

        $body     = wp_json_encode( array( 'ids' => $ids ) );
        $api_url  = $api_base . '/staged-transactions/reject';
        $response = event_manager_aws_sigv4_request( $api_url, 'POST', $body );

        if ( is_wp_error( $response ) ) {
            set_transient( 'em_action_error_' . $transaction_id, 'Reject request failed: ' . $response->get_error_message(), 30 );
            wp_redirect( admin_url( 'admin.php?page=event-manager&action=multi-view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
            exit;
        }

        $response_code = wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $data          = json_decode( $response_body, true );

        if ( $response_code === 200 && isset( $data['total'] ) ) {
            $total    = isset( $data['total'] ) ? intval( $data['total'] ) : 0;
            $rejected = isset( $data['rejected'] ) ? intval( $data['rejected'] ) : 0;
            $failed   = isset( $data['failed'] ) ? intval( $data['failed'] ) : 0;

            if ( $failed === 0 ) {
                set_transient( 'em_action_success_' . $transaction_id, $rejected . ' event(s) rejected successfully.', 30 );
                wp_redirect( admin_url( 'admin.php?page=event-manager&tab=bulk-review' ) );
                exit;
            }

            $error_msg = $rejected . ' rejected, ' . $failed . ' failed out of ' . $total;
            set_transient( 'em_action_error_' . $transaction_id, $error_msg, 30 );
        } else {
            set_transient( 'em_action_error_' . $transaction_id, 'Reject request failed. Status Code: ' . $response_code . '. Response: ' . $response_body, 30 );
        }

        wp_redirect( admin_url( 'admin.php?page=event-manager&action=multi-view&id=' . urlencode( $transaction_id ) . '&em_action=error' ) );
        exit;
    }
}
add_action( 'admin_init', 'event_manager_process_multiple_selected' );

function event_manager_render_multiple_transaction( $transaction_id ) {
    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/staged-transactions/' . urlencode( $transaction_id );
    $response = event_manager_aws_sigv4_request( $api_url );

    $transaction = null;
    $error_msg = '';
    $success_msg = '';
    $event_list_screenshot = '';
    $sub_transactions = array();

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

    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            $transaction = $data;
            $transaction['id'] = $transaction_id;
            $event_list_screenshot = $data['screenshot'] ?? '';
            $sub_transactions = isset( $data['transactions'] ) && is_array( $data['transactions'] ) ? $data['transactions'] : array();
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/multiple-transaction.php';
}
