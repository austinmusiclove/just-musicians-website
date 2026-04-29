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

require_once plugin_dir_path( __FILE__ ) . 'aws-helper.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/admin-page.php';

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
