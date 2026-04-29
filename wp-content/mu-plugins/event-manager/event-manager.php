<?php
/**
 * Plugin Name: Event Manager
 * Description: Custom page on the WP admin back end for event operations.
 * Version: 1.0.0
 * Author: John Filippone
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'aws-helper.php';

/**
 * Register the admin menu page for Event Manager.
 */
function event_manager_add_admin_menu() {
    add_menu_page(
        'Event Manager',                  // Page title
        'Event Manager',                  // Menu title
        'manage_options',                 // Capability required
        'event-manager',                  // Menu slug
        'event_manager_render_admin_page',// Callback function to render the page
        'dashicons-database-view',        // Menu icon
        25                                // Position
    );
}
add_action( 'admin_menu', 'event_manager_add_admin_menu' );

/**
 * Render the Event Manager admin page.
 */
function event_manager_render_admin_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Fetch data from API
    $api_url = 'https://djzf8h92k0.execute-api.us-east-2.amazonaws.com/prod/staged-transactions/events';
    $response = event_manager_aws_sigv4_request( $api_url );

    $staged_count = 0;
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
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p>Welcome to the Event Manager. Use this page to manage event operations.</p>

        <div class="card" style="max-width: 400px; margin-top: 20px;">
            <h2>Staged Transactions</h2>
            <?php if ( ! empty( $error_msg ) ) : ?>
                <div class="notice notice-error inline" style="margin: 10px 0;">
                    <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
                </div>
            <?php else : ?>
                <p style="font-size: 1.5em; font-weight: bold; color: #2271b1;">
                    Total: <?php echo esc_html( $staged_count ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
