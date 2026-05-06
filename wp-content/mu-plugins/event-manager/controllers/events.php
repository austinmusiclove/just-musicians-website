<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function event_manager_render_events() {
    $events = array();
    $error_msg = '';

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/events.php';
}
