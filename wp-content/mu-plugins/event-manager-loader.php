<?php
/**
 * Plugin Name: Event Manager Loader
 * Description: Loads the Event Manager plugin from its subdirectory.
 * Version: 1.0.0
 * Author: John Filippone
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require WPMU_PLUGIN_DIR . '/event-manager/event-manager.php';
