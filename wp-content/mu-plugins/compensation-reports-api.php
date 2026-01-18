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
