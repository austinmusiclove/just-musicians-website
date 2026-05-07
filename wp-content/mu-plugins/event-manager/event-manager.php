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
require_once plugin_dir_path( __FILE__ ) . 'controllers/router.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/dashboard.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/events.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/transaction-actions.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/bulk-actions.php';
require_once plugin_dir_path( __FILE__ ) . 'controllers/single-transaction.php';

/**
 * Register the admin menu page and submenus for Event Manager.
 */
function event_manager_add_admin_menu() {
    $menu_slug = 'event-manager';

    add_menu_page(
        'Event Manager',                  // Page title
        'Event Manager',                  // Menu title
        'manage_options',                 // Capability required
        $menu_slug,                       // Menu slug
        'event_manager_admin_router',     // Callback function to render the page
        'dashicons-database-view',        // Menu icon
        25                                // Position
    );

    add_submenu_page(
        $menu_slug,                       // Parent slug
        'Staged Transactions',            // Page title
        'Staged Transactions',            // Menu title
        'manage_options',                 // Capability required
        $menu_slug,                       // Menu slug (same as parent = redirects to dashboard)
        'event_manager_admin_router'      // Callback
    );

    add_submenu_page(
        $menu_slug,                       // Parent slug
        'Events',                         // Page title
        'Events',                         // Menu title
        'manage_options',                 // Capability required
        'event-manager-events',           // Menu slug
        'event_manager_admin_router'      // Callback
    );
}
add_action( 'admin_menu', 'event_manager_add_admin_menu' );
