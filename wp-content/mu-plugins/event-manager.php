<?php
/**
 * Plugin Name: Event Manager
 * Description: Custom page on the WP admin back end for event operations.
 * Version: 1.0.0
 * Author: opencode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p>Welcome to the Event Manager. Use this page to manage event operations.</p>
    </div>
    <?php
}
