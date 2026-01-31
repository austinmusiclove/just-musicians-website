<?php

/**
 * Plugin Name: Hire More Musicians Compensation Reports
 * Description: A custom plugin to manage compensation reports
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'compensation-reports-api/parse-args.php';
require_once 'compensation-reports-api/validate-args.php';
require_once 'compensation-reports-api/create-compensation-report.php';
require_once 'compensation-reports-api/sync-to-google-sheets.php';

// Rest APIs
add_action('rest_api_init', function () {
    register_rest_route('comp_reports/v1', 'sheets', [
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'sync_comp_reports_to_google_sheet',
    ]);
});
