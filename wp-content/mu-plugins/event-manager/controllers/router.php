<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Route the admin page request to the appropriate controller.
 */
function event_manager_admin_router() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
    $transaction_id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';

    if ( 'view' === $action && ! empty( $transaction_id ) ) {
        event_manager_render_single_transaction( $transaction_id );
        return;
    }

    event_manager_render_dashboard();
}
